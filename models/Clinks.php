<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 21.10.2018
 * Time: 14:47
 */

namespace app\models;

use yii\db\ActiveRecord;

// Модель связанная с таблицей clinks (связи категорий между собой)
class Clinks extends ActiveRecord
{
    public function getChild(){
        return $this->hasMany(Categorys::className(), ['id' => 'id_category_child']);
    }

    public function getParent(){
        return $this->hasMany(Categorys::className(), ['id' => 'id_category_parent']);
    }

    public function attributeLabels()
    {
        return [
            'id_category_parent' => 'категория'
        ];
    }

    public function rules()
    {
        return [
            ['id_category_parent', 'required'],
            ['id_category_parent', 'trim']
        ];
    }
}