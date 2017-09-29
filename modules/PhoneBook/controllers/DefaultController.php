<?php

namespace app\modules\PhoneBook\controllers;

use Yii;
use yii\web\Controller;
use app\modules\PhoneBook\models\ContactSearch;

/**
 * Default controller for the `PhoneBook` module
 */
class DefaultController extends Controller {

    public function actionIndex() {

        $searchModel = new ContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
