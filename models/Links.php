<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 21.10.2018
 * Time: 14:46
 */

namespace app\models;

use yii\db\ActiveRecord;

// Модель связанная с таблицей links (связи товаров и категорий)
class Links extends ActiveRecord
{
    public function getGoods(){
        return $this->hasMany(Goods::className(), ['id' => 'id_good']);
    }

    public function getCategorys(){
        return $this->hasMany(Categorys::className(), ['id' => 'id_category']);
    }

    public function attributeLabels()
    {
        return [
            'id_category' => 'категория'
        ];
    }

    public function rules()
    {
        return [
            ['id_category', 'required'],
            ['id_category', 'trim']
        ];
    }
}