<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// Форма создания товара

// выводим форму и поля
$input = ActiveForm::begin();
echo $input->field($form, 'good')->label('Имя нового товара');
echo $input->field($form, 'price')->label('Цена товара');
echo $input->field($form, 'number')->label('Количество');
echo $input->field($l_form, 'id_category')->label('Номер категории');

// выводим список категорий
echo "<ul>";
foreach ($cat as $c){
    echo "<li>Категория: {$c['id']} > \"{$c['category']}\"</li>";
}
echo "</ul>";

echo Html::submitButton('Создать товар');
ActiveForm::end();

// выводим сообщение об успехе или ошибку
echo $msg;

// ссылка на главную страницу
echo "<a href='index.php?r=site/index'>Назад</a>";