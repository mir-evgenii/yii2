<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 21.10.2018
 * Time: 14:46
 */

namespace app\models;

use yii\db\ActiveRecord;

// Модель связанная с таблицей categorys (категории)
class Categorys extends ActiveRecord
{
    public function getGoods(){
        return $this->hasMany(Links::className(), ['id_good' => 'id']);
    }

    public function getCategorysChild(){
        return $this->hasMany(Clinks::className(), ['id_category_child' => 'id']);
    }

    public function getCategorysParent(){
        return $this->hasMany(Clinks::className(), ['id_category_parent' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'category' => 'категория'
        ];
    }

    public function rules()
    {
        return [
            ['category', 'string', 'min' => 3, 'max' => 32],
            ['category', 'required'],
            ['category', 'trim']
        ];
    }
}