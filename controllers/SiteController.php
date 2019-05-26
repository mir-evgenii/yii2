<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\Goods;
use app\models\Categorys;
use app\models\Links;
use app\models\Clinks;
use Yii;

class SiteController extends Controller
{
    // переменные необходимые для вызова конструктора parent::__construct()
    // родительского класса Controller
    public $id;
    public $module;

    public $arr_all_categorys;
    public $form_category;
    public $form_clink;
    public $form_good;
    public $form_link;
    public $msg = null;

    public function __construct($id, $module, $config = [])
    {
        // вызов котроллера родительского класса Controller
        parent::__construct($id, $module, $config = []);

        $this->arr_all_categorys = $this->getCategorysArr();
        $this->form_category = new Categorys();
        $this->form_clink = new Clinks();
        $this->form_good = new Goods();
        $this->form_link = new Links();
    }

    // $id - текущая категория,
    // по умолчанию "0" то есть "Корневая"
    public function actionIndex(
        $id = 0, 
        $id_del_category = null,
        $id_del_good = null
    )
    {
        if ($id_del_category) {
            $this->msg = $this->deleteCategory($id_del_category);
        } elseif ($id_del_good) {
            $this->msg = $this->deleteGood($id_del_good);
        }

        $name_this_category = Categorys::findOne($id)->category;
        $id_parent_category = Clinks::find()
            ->where(['id_category_child' => $id])
            ->one();

        $categorys = Clinks::find()
            ->where(['id_category_parent' => $id])
            ->groupBy('id_category_child')
            ->all();

        $goods = Links::find()
            ->where(['id_category' => $id])
            ->groupBy('id_good')
            ->all();

        return $this->render('index', [
            'id' => $id, 
            'id_parent_category' => $id_parent_category,
            'categorys' => $categorys,
            'goods' => $goods,
            'msg' => $this->msg,
            'name_this_category' => $name_this_category
        ]);
    }

    public function actionCreateCategory()
    {
        if ($this->form_category->load(Yii::$app->request->post())
            && $this->form_clink->load(Yii::$app->request->post())) {

            if ($this->form_category->validate()
                && $this->form_clink->validate()) {
                
                $this->form_category->save();
                $this->form_clink->save();
                $this->msg = ['Категория создана!', 1];
            } else {
                $this->msg = ['Категория не создана!', 0];
            }
        }

        return $this->render('create_category', [
            'form_category' => $this->form_category,
            'form_clink' => $this->form_clink,
            'msg' => $this->msg,
            'arr_all_categorys' => $this->arr_all_categorys
        ]);
    }

    public function actionCreateGood()
    {
        if ($this->form_good->load(Yii::$app->request->post())
            && $this->form_link->load(Yii::$app->request->post())) {
            
            if ($this->form_good->validate() && $this->form_link->validate()) {
                $this->form_good->save();
                $this->form_link->save();
                $this->msg = ['Товар создан!', 1];
            } else {
                $this->msg = ['Товар не создан!', 0];
            }
        }

        return $this->render('create_good', [
            'form_good' => $this->form_good,
            'form_link' => $this->form_link,
            'msg' => $this->msg,
            'arr_all_categorys' => $this->arr_all_categorys
        ]);
    }

    public function actionChangeCategory($id = null)
    {
        $this->form_category = Categorys::findOne($id);
        $this->form_clink = Clinks::find()
            ->where(['id_category_child' => $id])
            ->one();

        if ($this->form_category->load(Yii::$app->request->post())
            && $this->form_clink->load(Yii::$app->request->post())) {
            
            if ($this->form_category->save() && $this->form_clink->save()) {
                $this->msg = ['Категория изменена!', 1];
            } else {
                $this->msg = ['Категория не изменена!', 0];
            }
        }

        $id_parent_category = Clinks::find()
            ->where(['id_category_child' => $id])
            ->one();

        return $this->render('change_category', [
            'form_category' => $this->form_category,
            'form_clink' => $this->form_clink,
            'msg' => $this->msg,
            'arr_all_categorys' => $this->arr_all_categorys,
            'id_parent_category' => $id_parent_category
        ]);
    }

    public function actionChangeGood($id = null)
    {
        $this->form_good = Goods::findOne($id);
        $this->form_link = Links::find()->where(['id_good' => $id])->one();

        if ($this->form_good->load(Yii::$app->request->post())
            && $this->form_link->load(Yii::$app->request->post())) {

            if ($this->form_good->save() && $this->form_link->save()) {
                $this->msg = ['Товар изменен!', 1];
            } else {
                $this->msg = ['Товар не изменен!', 0];
            }
        }

        $id_parent_category = Links::find()->where(['id_good' => $id])->one();

        return $this->render('change_good', [
            'form_good' => $this->form_good,
            'form_link' => $this->form_link,
            'msg' => $this->msg,
            'arr_all_categorys' => $this->arr_all_categorys,
            'id_parent_category' => $id_parent_category
        ]);
    }

    private function deleteCategory($id_del_category)
    {
        $del = $this->checkDeleteCategory($id_del_category);
        
        if ($del[0] === true) {
            $del[1][0]->delete();
            $del[1][1]->delete();
            $msg = ['Категория удалена!', 1];
            return $msg;
        } else {
            return $del[1];
        }
    }

    private function deleteGood($id_del_good)
    {
        $del[0] = Goods::findOne($id_del_good);
        $del[1] = Links::findOne($id_del_good);

        if ($del[0] != null) {
            $del[0]->delete();
            $del[1]->delete();
            $msg = ['Товар удален!', 1];
        } else {
            $msg = ['Товар не существует!', 0];
        }
        
        return $msg;
    }

    private function getCategorysArr()
    {
        $categorys = Categorys::find()->asArray()->all();
        foreach ($categorys as $c) {
            $arr_all_categorys[$c['id']] = $c['category'];
        }
        return $arr_all_categorys;
    }

    private function checkDeleteCategory($id_del_category)
    {
        $del[0] = Categorys::findOne($id_del_category);
        $form_link = Links::find()
            ->where(['id_category' => $id_del_category])
            ->all();
        $form_clink = Clinks::find()
            ->where(['id_category_parent' => $id_del_category])
            ->all();

        if ($form_clink) {
            $msg = ['Категория содержит вложенные категории!', 0];
            return [false, $msg];
        } elseif ($form_link) {
            $msg = ['Категория содержит товары!', 0];
            return [false, $msg];
        } elseif ($del[0] == null) {
            $msg = ['Категории не существует!', 0];
            return [false, $msg];
        } else {
            $del[1] = Clinks::findOne($id_del_category);
            return [true, $del];
        }
    }
}
