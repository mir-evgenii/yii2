<?php
namespace app\models;

use yii\db\ActiveRecord;

class Categorys extends ActiveRecord
{
    public function getGoods(){
        return $this->hasMany(Links::className(),
            ['id_good' => 'id']);
    }

    public function getCategorysChild(){
        return $this->hasMany(Clinks::className(),
            ['id_category_child' => 'id']);
    }

    public function getCategorysParent(){
        return $this->hasMany(Clinks::className(),
            ['id_category_parent' => 'id']);
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
            ['category', 'string', 'min' => 3, 'max' => 32, 
            'message' => 'Название категории должно быть от 3 до 32 символов.'],
            ['category', 'required', 
            'message' => 'Название категории обязательное поле.'],
            ['category', 'trim']
        ];
    }
}
