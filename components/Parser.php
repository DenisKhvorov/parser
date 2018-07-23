<?php

namespace app\components;


use yii\base\Model;
use GuzzleHttp\Client;
use app\models\OfficesModel;
use yii\console\Exception;

class Parser
{
    // число успешно сохраненных записей
    public $totalSaved = 0;

    // число не записанных записей, ощибки валидации
    public $totalErrors = 0;

    // функция перебора страниц, функция прервется в момент когда не найдет следующей страницы и вернет колличество сохраненных записей, а так же число ошибок
    public function parsePage($page = 0)
    {
        $content = self::getSiteContent($page);
        if ($this->saveDataFromContent($content)) {
            return $this->parsePage($page + 1);
        } else {
            return [$this->totalSaved, $this->totalErrors];
        }
    }

    // функция для получения контента сайта, использовался Phpquery
    public static function getSiteContent($page)
    {
        try {
            $res = (new Client())->request('GET', 'https://realt.by/sale/offices/?page=' . $page);
            $body = $res->getBody();
            return \phpQuery::newDocumentHTML($body);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
    // функция собирает массив обработанных данных, после чего сохраняет его в базу данных
    public function saveDataFromContent($content)
    {
        $office = new OfficesModel();
        if (isset($content['.bd-item'])) {
            foreach ($content['.bd-item'] as $key) {
                $office->attributes = [
                    'office_title' => pq($key)->find('.title a')->text(),
                    'office_price' => pq($key)->find('.price-byr')->text(),
                    'office_numbers' => pq($key)->find('.mb0')->text()
                ];
                if ($office->validate()) {
                    $office->dataForInsert[] = $office->getAttributes(['office_title', 'office_price', 'office_numbers']);
                } else {
                    $this->totalErrors++;
                }
            }
        } else {
            return false;
        }

        if (!$office->dataForInsert) {
            return true;
        } elseif ($count = $office->saveData()) {
            $this->totalSaved += $count;
            return true;
        }

        return false;
    }
}