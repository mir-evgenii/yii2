<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 21.10.2018
 * Time: 11:11
 */

namespace app\models;

use yii\db\ActiveRecord;

// Модель связанная с таблицей goods (товары)
class Goods extends ActiveRecord
{
    public function getCategorys(){
        return $this->hasMany(Links::className(), ['id_category' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'good' => 'товар',
            'price' => 'цена',
            'number' => 'количество',
        ];
    }

    public function rules()
    {
        return [
            ['good', 'string', 'min' => 3, 'max' => 32],
            [['good', 'price', 'number'], 'required'],
            [['good', 'price', 'number'], 'trim']
        ];
    }
}