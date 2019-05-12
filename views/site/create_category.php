<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// Форма создания категории

// выводим форму и поля
$input = ActiveForm::begin();
echo $input->field($form, 'category')->label('Имя новой категории');
echo $input->field($l_form, 'id_category_parent')->label('Номер родительской категории');

// выводим список категорий
echo "<select class='custom-select' id='inputGroupSelect01'>";
foreach ($cat as $c){
    echo "<option value='{$c['id']}'>{$c['category']}</option>";
}
echo "</select>";

// ссылка на главную страницу
echo "<a class='btn btn-primary' href='index.php?r=site/index' role='button'>Назад</a>";

echo Html::submitButton('Создать категорию', ['class' => 'btn btn-primary', 'type' => 'submit']);

ActiveForm::end();

// выводим сообщение об ошибке или успехе
echo $msg;

