<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 19.10.2018
 * Time: 11:22
 */

namespace app\controllers;
use yii\web\Controller;
use app\models\Goods;
use app\models\Categorys;
use app\models\Links;
use app\models\Clinks;
use Yii;

class SiteController extends Controller
{

    // главная страница
    public function actionIndex($id = null, $c_del = null, $g_del = null)
    {

        // удаление категории
        if ($c_del) {
            // выбираем категорию
            $del = Categorys::findOne($c_del);

            // проверяем является ли категория родительской
            $query = "SELECT * FROM clinks WHERE id_category_parent LIKE {$c_del}";
            $l_form = Clinks::findBySql($query)->one();

            // проверяем содержатся ли в категории товары
            $query = "SELECT * FROM links WHERE id_category LIKE {$c_del}";
            $r_form = Links::findBySql($query)->one();

            if ($l_form) {
                // если категория является родительской то не удаляем ее а выводим сообщение
		$msg = $this->createMessаge('Родительская категория не может быть удалена!', 0);
                return $this->render('index', compact('msg'));
            } elseif ($r_form) {
		 // если категория содержит товары то не удаляем ее а выводим сообщение
		$msg = $this->createMessаge('Категорию нельзя удалить пока в ней содержатся товары!', 0);
                return $this->render('index', compact('msg'));
            } else {
                // если категория не является родительской и не содержит товары то удаляем ее
                $del->delete();
                // удаляем связи категории
                $del = Clinks::findOne($c_del);
                $del->delete();
		$msg = $this->createMessаge('Категория удалена!', 1);
                return $this->render('index', compact('msg'));
            }
        }


        // удаление товара
        if ($g_del) {
            // выбираем и удаляем товар
            $del = Goods::findOne($g_del);
            $del->delete();
            // удаляем связь товара с кататегорией
            $del = Links::findOne($g_del);
            $del->delete();
	    $msg = $this->createMessаge('Товар удален!', 1);
        } else {
            $msg = null;
        }


        // выводим каталог товаров и категорий
        if ($id){
            // выбираем текущюю категорию по id
            $cat = Categorys::findOne($id);
            // записываем имя текущей категрии cat
            $cat = $cat->category;

            // выбираем все категории являюшиеся вложенными в текущюю категорию
            $query = "SELECT * FROM clinks WHERE id_category_parent = {$id} GROUP BY id_category_child";
            $childs = Clinks::findBySql($query)->with('child')->all();

            // выбираем все товары вложенные в текущюю категорию
            $query = "SELECT * FROM links WHERE id_category = {$id} GROUP BY id_good";
            $goods = Links::findBySql($query)->with('goods')->all();
        } else {
            // если id не указан, то есть страница Index то выводим категории которые не являются вложенными
            // то есть выводим категории первого уровня
            // текущяя директория равна нулю
            $cat = null;
            $query = "SELECT * FROM clinks WHERE id_category_parent != id_category_child GROUP BY id_category_parent";
            $parents = Clinks::findBySql($query)->with('parent')->all();
        }
        return $this->render('index', compact('parents', 'childs', 'goods', 'msg', 'cat'));
    }



    // Создание категории
    public function actionCreateCategory()
    {
        // создаем новый обьект категории и обьект связи с другими категориями
        $form = new Categorys();
        $l_form = new Clinks();

        if ( $form->load(Yii::$app->request->post()) && $l_form->load(Yii::$app->request->post()) ){
            // если данные переданы сохраняем их и выводим сообщение об успехе
            // иначе выводим сообщение об ошибке
            if($form->save() && $l_form->save()){
		$msg = $this->createMessаge('Категория создана!', 1);
            } else {
		$msg = $this->createMessаge('Категория не создана!', 0);
            }
        } else {
            $msg = null;
        }

        // выбираем все категории и передаем в форму создания категории
        $query = "SELECT * FROM categorys";
        $cat = Categorys::findBySql($query)->asArray()->all();

        return $this->render('create_category', compact('form', 'l_form', 'msg', 'cat'));
    }



    // Создание товара
    public function actionCreateGood()
    {
        // Создаем обьект товара и обьект связи с категориями
        $form = new Goods();
        $l_form = new Links();

        if ( $form->load(Yii::$app->request->post()) && $l_form->load(Yii::$app->request->post()) ){
            // если переданы данные сохраняем их и выводим сообщение об успехе иначе ошибка
            if($form->save() && $l_form->save()){
		$msg = $this->createMessаge('Товар создан!', 1);
            } else {
		$msg = $this->createMessаge('Товар не создан!', 0);
            }
        } else {
            $msg = null;
        }

        // выбираем все категории и передаем в форму создания товара
        $query = "SELECT * FROM categorys";
        $cat = Categorys::findBySql($query)->asArray()->all();

        return $this->render('create_good', compact('form', 'l_form', 'msg', 'cat'));
    }



    // Изменение категории
    public function actionChangeCategory($id = null)
    {
        // выбираем категорию по id
        $form = Categorys::findOne($id);

        // выбираем категорию к которой принадлежит изменяемая категория
        $query = "SELECT * FROM clinks WHERE id_category_child LIKE {$id}";
        $l_form = Clinks::findBySql($query)->one();


        if (!($l_form)){
            // если у категории нет родительской категории (категория первого уровня)
            // то работаем только с обьектом категории
            // то есть только с именем категории
            $l_form = null;
            if ( $form->load(Yii::$app->request->post())) {
                // если данные получены сохраняем их и выводим сообщение об успехе иначе ошибка
                if ($form->save()) {
		    $msg = $this->createMessаge('Категория изменена!', 1);
                } else {
		    $msg = $this->createMessаge('Категория не изменена!', 0);
                }
            } else {
                $msg = null;
            }
        } else {
            // если у категории есть родительская категория
            // то работаем с обоими обьектами
            if ( $form->load(Yii::$app->request->post()) && $l_form->load(Yii::$app->request->post()) ) {
                // если данные получены сохраняем их и выводим сообщение об успехе иначе ошибка
                if ($form->save() && $l_form->save()) {
		    $msg = $this->createMessаge('Категория изменена!', 1);
                } else {
		    $msg = $this->createMessаge('Категория не изменена!', 0);
                }
            } else {
                $msg = null;
            }
        }


        // выбираем все категории и передаем в форму изменения категории
        $query = "SELECT * FROM categorys";
        $cat = Categorys::findBySql($query)->asArray()->all();

        return $this->render('change_category', compact('form', 'l_form', 'msg', 'cat'));
    }



    // Изменение товара
    public function actionChangeGood($id = null)
    {
        // выбираем товар по id
        $form = Goods::findOne($id);

        // выбираем категорию к которой принадлежит товар
        $query = "SELECT * FROM links WHERE id_good LIKE {$id}";
        $l_form = Links::findBySql($query)->one();

        if ( $form->load(Yii::$app->request->post()) && $l_form->load(Yii::$app->request->post())) {
            // если данные переданы сохраняем их и выводим сообщение об успехе иначе ошибка
            if ($form->save() && $l_form->save()) {
		$msg = $this->createMessаge('Товар изменен!', 1);
            } else {
		$msg = $this->createMessаge('Товар не изменен!', 0);
            }
        } else {
            $msg = null;
        }

        // выбираем все категории и передаем в форму изменения товара
        $query = "SELECT * FROM categorys";
        $cat = Categorys::findBySql($query)->asArray()->all();

        return $this->render('change_good', compact('form', 'l_form', 'msg', 'cat'));
    }

    private function createMessаge($msg, $color)
    {
	    if ($color==1) {
	   	$msg = '<div class="alert alert-success" role="alert">'.$msg.' <a class="btn btn-success" href="index.php?r=site/index" role="button">OK</a></div>'; 
	    } else {
	   	$msg = '<div class="alert alert-danger" role="alert">'.$msg.' <a class="btn btn-danger" href="index.php?r=site/index" role="button">OK</a></div>';  
	    }
	    return $msg;
    }
}
