<?php
namespace app\models;

use yii\db\ActiveRecord;

class Clinks extends ActiveRecord
{
    public function getChild(){
        return $this->hasMany(Categorys::className(),
            ['id' => 'id_category_child']);
    }

    public function getParent(){
        return $this->hasMany(Categorys::className(),
            ['id' => 'id_category_parent']);
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
            ['id_category_parent', 'integer', 
            'message' => 'Id категории должно быть целым числом.'],
            ['id_category_parent', 'exist', 
            'message' => 'Категории с таким id нет.'],
            ['id_category_parent', 'required', 
            'message' => 'Id категории обязательный параметр.'],
            ['id_category_parent', 'trim']
        ];
    }
}
