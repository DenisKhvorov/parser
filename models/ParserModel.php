<?php

namespace app\models;


use yii\base\Model;
use GuzzleHttp\Client;

class ParserModel {

    public static function parse(){
        $client = new Client();
        $res = $client->request('GET', 'https://realt.by/sale/offices/');
        $body = $res->getBody();
        $document = \phpQuery::newDocumentHTML($body);
        self::getArray($document);
    }

    public function getArray($document){
        $result = [];
        $k = 0;
        foreach ($document['.bd-item'] as $key){
            $result[$k] = ['title' =>pq($key)->find('.title a')->text(),
                'price' => pq($key)->find('.price-byr')->text(),
                'number' => pq($key)->find('.mb0')->text()
            ];
            $k++;
        }
        OfficesModel::validateArray($result);
    }

}