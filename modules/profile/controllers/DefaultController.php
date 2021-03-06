<?php

namespace app\modules\profile\controllers;

use app\models\OfficesModel;
use yii\web\Controller;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\User;
use app\models\OfficesModelSearch;
use Yii;

/**
 * Default controller for the `profile` module
 */
class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' =>[
                            User::ROLE_ADMIN
                        ],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new OfficesModelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
