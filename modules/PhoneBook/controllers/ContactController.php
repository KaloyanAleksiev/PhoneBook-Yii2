<?php

namespace app\modules\PhoneBook\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\PhoneBook\models\Contact;
use app\modules\PhoneBook\models\ContactSearch;
use app\modules\PhoneBook\models\Phone;

/**
 * ContactController implements the CRUD actions for Contact model.
 */
class ContactController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Contact models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contact model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $dataProvider = new ActiveDataProvider([
            'query' => Phone::find()->where(['contact_id' => $id]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new Contact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Contact();
        $phonesModel = [new Phone];

        if ($model->load(Yii::$app->request->post())) {

            $phonesModel = Phone::createMultiple(Phone::classname());
            Phone::loadMultiple($phonesModel, Yii::$app->request->post());

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                                ActiveForm::validateMultiple($phonesModel), ActiveForm::validate($model)
                );
            }

            // validate all models
            $valid = $model->validate();
            $valid = Phone::validateMultiple($phonesModel) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($phonesModel as $phoneModel) {
                            $phoneModel->contact_id = $model->id;
                            if (!($flag = $phoneModel->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'phonesModel' => (empty($phonesModel)) ? [new Phone] : $phonesModel
        ]);
    }

    /**
     * Updates an existing Contact model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $phonesModel = $model->phones;

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($phonesModel, 'id', 'id');
            $phonesModel = Phone::createMultiple(Phone::classname(), $phonesModel);
            Phone::loadMultiple($phonesModel, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($phonesModel, 'id', 'id')));


            // validate all models
            $valid = $model->validate();
            $valid = Phone::validateMultiple($phonesModel) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            Phone::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($phonesModel as $modelPhone) {
                            $modelPhone->contact_id = $model->id;
                            if (!($flag = $modelPhone->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'phonesModel' => (empty($phonesModel)) ? [new Phone] : $phonesModel
        ]);
    }

    /**
     * Deletes an existing Contact model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['default/index']);
    }

    /**
     * Finds the Contact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Contact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
