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
    /**
     * @var array for validated array
     */
    public $dataForInsert = [];

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'offices';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['office_title', 'office_price', 'office_numbers'], 'required'],
            [['office_title', 'office_price', 'office_numbers'], 'trim'],
            [['office_title', 'office_numbers', 'office_price'], 'string', 'max' => 255],
            ['office_title', 'unique', 'targetClass' => 'app\models\OfficesModel'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'office_title' => 'Office Title',
            'office_price' => 'Office Price',
            'office_numbers' => 'Office Numbers',
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $this->office_price = preg_replace('/[^0-9]/', '', $this->office_price);
        return parent::beforeValidate();
    }

    /**
     * @return bool|int
     */
    public function saveData()
    {
        if ($this->dataForInsert) {
            return Yii::$app->db->createCommand()->batchInsert(
                OfficesModel::tableName(),
                ['office_title', 'office_price', 'office_numbers'],
                $this->dataForInsert
            )->execute();
        }
        return false;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->office_price . ' руб/кв.м';
    }
}
