<?php

namespace app\commands;


use app\models\ParserModel;
use yii\console\Controller;
use GuzzleHttp\Client;


class ParserController extends Controller {

    public function actionIndex(){
        ParserModel::parse();
    }

}