<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "offices".
 *
 * @property int $id
 * @property string $office_title
 * @property string $office_price
 * @property string $office_numbers
 */
class OfficesModel extends \yii\db\ActiveRecord
{


    public static function tableName()
    {
        return 'offices';
    }


    public function rules()
    {
        return [
            [['office_title', 'office_price', 'office_numbers'], 'required'],
            [['office_title', 'office_price', 'office_numbers'], 'string', 'max' => 255],
            ['office_title','unique','targetClass'=>'app\models\OfficesModel'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'office_title' => 'Office Title',
            'office_price' => 'Office Price',
            'office_numbers' => 'Office Numbers',
        ];
    }

    public static function validateArray($result){
        $data = [];
        $k = 0;
        foreach ($result as $val){
            $office = new OfficesModel();
            $office->office_title = $val['title'];
            $office->office_price = $val['price'];
            $office->office_numbers = $val['number'];
            if($office->validate()){
                $data[$k] = ['title' => $office->office_title,
                    'price' => $office->office_price ,
                    'number' => $office->office_numbers
                ];
                $k++;
            }
        }
        self::saveData($data);
    }

    public function saveData($data){
        Yii::$app->db->createCommand()->batchInsert(
            OfficesModel::tableName(),
            ['office_title', 'office_price', 'office_numbers'],
            $data
        )->execute();
    }

    public function search($params)
    {
        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'defaultOrder'=>[
                    'id'=>SORT_DESC
                ]
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'office_title', $this->office_title])
            ->andFilterWhere(['like', 'office_price', $this->office_price]);
//            ->andFilterWhere(['like', 'office_numbers', $this->office_numbers]);

        return $dataProvider;
    }
}
