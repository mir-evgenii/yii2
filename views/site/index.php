<?php

// Главная страница

// сообщение об ошибке или успехе
echo $msg;



// выводим категории первого уровня
if(@$parents){
    echo "<h4>Категории</h4>
	  <table class='table'>
	  <thead class='thead-light'>
            <tr>
                <th scope='col'>Название</th>
                <th scope='col'></th>
                <th scope='col'></th>
	    <tr>
	  </thead>
    ";
    foreach($parents as $parent){
        echo "<tbody><tr>";
        echo "<td><a href='index.php?r=site/index&id={$parent->parent['0']->id}'>".$parent->parent['0']->category."</a></td>
              <td><a href='index.php?r=site/change-category&id={$parent->parent['0']->id}'>Изменить</a></td>
              <td><a href='index.php?r=site/index&c_del={$parent->parent['0']->id}'>Удалить</a></td>
              ";
        echo "</tbody></tr>";
    }
    echo "</table>";
}



// выводим вложенные категории
if(@$childs){
    echo "<h4>Категории вложенные в категорию \"{$cat}\"</h4>
          <table class='table'>
	  <thead class='thead-light'>
            <tr>
                <th scope='col'>Название</th>
                <th scope='col'></th>
                <th scope='col'></th>
            <tr>
	  </thead>
    ";
    foreach($childs as $child){
        echo "<tbody><tr>";
        echo "<td><a href='index.php?r=site/index&id={$child->child['0']->id}'>".$child->child['0']->category."</a></td>
              <td><a href='index.php?r=site/change-category&id={$child->child['0']->id}'>Изменить</a></td>
              <td><a href='index.php?r=site/index&c_del={$child->child['0']->id}'>Удалить</a></td>
              ";
        echo "</tbody></tr>";
    }
    echo "</table>";
}



// выводим товары
if(@$goods){
    echo "<h4>Товары категории \"{$cat}\"</h4>
          <table class='table'>
	  <thead class='thead-light'>
            <tr>
                <th scope='col'>Название</th>
                <th scope='col'>Цена</th>
                <th scope='col'>Кол-во</th>
                <th scope='col'></th>
                <th scope='col'></th>
	    </tr>
	  </thead>";
    foreach($goods as $good){
        echo "<tbody><tr>";
        echo "<td>".$good->goods['0']->good."</td>
              <td>".$good->goods['0']->price."</td>
              <td>".$good->goods['0']->number."</td>
              <td><a href='index.php?r=site/change-good&id={$good->goods['0']->id}'>Изменить</a></td>
              <td><a href='index.php?r=site/index&g_del={$good->goods['0']->id}'>Удалить</a></td>";
        echo "</tbody></tr>";
    }
    echo "</table>";
}

// если мы находимся во вложенной категории выводим ссылку на главную страницу
if (@$cat) {
    echo "<a class='btn btn-primary' href='index.php?r=site/index' role='button'>Назад</a>";
}
