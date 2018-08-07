<?php

namespace app\commands;

use app\components\Parser;
use yii\console\Controller;
use GuzzleHttp\Client;
use yii\helpers\Console;


class ParserController extends Controller
{
    /**
     *  yii migrate;
     *  yii parser;
     */
    public function actionIndex()
    {
        list($result, $errors) = (new Parser())->parsePage();
        if ($errors) {
            echo "Total errors: $errors.\r\n";
        }
        if ($result) {
            echo "Total saved: $result.";
        }
    }
}