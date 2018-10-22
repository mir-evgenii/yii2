<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// Форма изменения категории

// выводим форму и поля
$input = ActiveForm::begin();
echo $input->field($form, 'category')->label('Имя категории');

// проверяем переданы ли данные о родительской категории
if ($l_form){
    echo $input->field($l_form, 'id_category_parent')->label('Номер родительской категории');
} else {
    echo "Это корневая категория, она не вложена не в одну категорию.";
}

// выводим список категорий
echo "<ul>";
foreach ($cat as $c){
    echo "<li>Категория: {$c['id']} > \"{$c['category']}\"</li>";
}
echo "</ul>";

echo Html::submitButton('Изменить категорию');
ActiveForm::end();

// выводим сообщение об успехе или ошибку
echo $msg;

// ссылка на главную страницу
echo "<a href='index.php?r=site/index'>Назад</a>";