<?php
namespace app\models;

use yii\db\ActiveRecord;

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
            ['id_category', 'integer', 
            'message' => 'Значение поля должно быть целым числом.'],
            ['id_category', 'required', 
            'message' => 'Значение поля обязательно для заполнения.'],
            ['id_category', 'trim']
        ];
    }
}
