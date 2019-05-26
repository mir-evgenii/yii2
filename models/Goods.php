<?php
namespace app\models;

use yii\db\ActiveRecord;

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
            ['good', 'string', 'min' => 3, 'max' => 32, 
            'message' => 'Название товара должно быть от 3 до 32 символов.'],
            [['good', 'price', 'number'], 'required', 
            'message' => 'Поле является обязательным для заполнения.'],
            [['good', 'price', 'number'], 'trim'],
            [['price', 'number'], 'integer', 
            'message' => 'Значение поля должно быть целым числом.']
        ];
    }
}
