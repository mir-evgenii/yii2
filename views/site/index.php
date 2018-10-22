<?php

// Главная страница

// сообщение об ошибке или успехе
echo $msg;

// ссылки на формы создания категории и товара
echo "<a href='index.php?r=site/create-category'>Создать категорию</a><br>";
echo "<a href='index.php?r=site/create-good'>Создать товар</a><br>";



// выводим категории первого уровня
if(@$parents){
    echo "<p>Категории</p>
          <table>
            <tr>
                <td>Название</td>
                <td></td>
                <td></td>
            <tr>
    ";
    foreach($parents as $parent){
        echo "<tr>";
        echo "<td><a href='index.php?r=site/index&id={$parent->parent['0']->id}'>".$parent->parent['0']->category."</a></td>
              <td><a href='index.php?r=site/change-category&id={$parent->parent['0']->id}'>Изменить</a></td>
              <td><a href='index.php?r=site/index&c_del={$parent->parent['0']->id}'>Удалить</a></td>
              ";
        echo "</tr>";
    }
    echo "</table>";
}



// выводим вложенные категории
if(@$childs){
    echo "<p>Категории вложенные в категорию \"{$cat}\"</p>
          <table>
            <tr>
                <td>Название</td>
                <td></td>
                <td></td>
            <tr>
    ";
    foreach($childs as $child){
        echo "<tr>";
        echo "<td><a href='index.php?r=site/index&id={$child->child['0']->id}'>".$child->child['0']->category."</a></td>
              <td><a href='index.php?r=site/change-category&id={$child->child['0']->id}'>Изменить</a></td>
              <td><a href='index.php?r=site/index&c_del={$child->child['0']->id}'>Удалить</a></td>
              ";
        echo "</tr>";
    }
    echo "</table>";
}



// выводим товары
if(@$goods){
    echo "<p>Товары категории \"{$cat}\"</p>
          <table>
            <tr>
                <td>Название</td>
                <td>Цена</td>
                <td>Кол-во</td>
                <td></td>
                <td></td>
            </tr>";
    foreach($goods as $good){
        echo "<tr>";
        echo "<td>".$good->goods['0']->good."</td>
              <td>".$good->goods['0']->price."</td>
              <td>".$good->goods['0']->number."</td>
              <td><a href='index.php?r=site/change-good&id={$good->goods['0']->id}'>Изменить</a></td>
              <td><a href='index.php?r=site/index&g_del={$good->goods['0']->id}'>Удалить</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}

// если мы находимся во вложенной категории выводим ссылку на главную страницу
if (@$cat) {
    echo "<a href='index.php?r=site/index'>Назад</a>";
}