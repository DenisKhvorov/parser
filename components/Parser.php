<?php

namespace app\components;


use yii\base\Model;
use GuzzleHttp\Client;
use app\models\OfficesModel;
use yii\console\Exception;

class Parser
{

    public $totalSaved = 0;


    public $totalErrors = 0;

    /**
     * @param int $page
     * @return array
     */
    public function parsePage($page = 0)
    {
        $content = self::getSiteContent($page);
        if ($this->saveDataFromContent($content)) {
            return $this->parsePage($page + 1);
        } else {
            return [$this->totalSaved, $this->totalErrors];
        }
    }

    /**
     * @param $page
     * @return \phpQueryObject
     */
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

    /**
     * @param $content
     * @return bool
     */
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