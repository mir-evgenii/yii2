<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// Форма создания категории

// выводим форму и поля
$input = ActiveForm::begin();
echo $input->field($form, 'category')->label('Имя новой категории');
echo $input->field($l_form, 'id_category_parent')->label('Номер родительской категории');

// выводим список категорий
echo "<ul>";
foreach ($cat as $c){
    echo "<li>Категория: {$c['id']} > \"{$c['category']}\"</li>";
}
echo "</ul>";

echo Html::submitButton('Создать категорию');
ActiveForm::end();

// выводим сообщение об ошибке или успехе
echo $msg;

// ссылка на главную страницу
echo "<a href='index.php?r=site/index'>Назад</a>";
