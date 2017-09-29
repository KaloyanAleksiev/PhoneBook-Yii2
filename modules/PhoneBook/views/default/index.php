<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\PhoneBook\models\ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Phone Book';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Contact', ['contact/create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'fullName',
                'label' => 'Full name',
                'value' => function($model) {
                    return $model->name . " " . $model->fname;
                },
            ],
            [
                'label' => 'Number of phones',
                'attribute' => 'phonesCount',
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],
            ['class' => 'yii\grid\ActionColumn',
                'urlCreator' => function( $action, $model, $key, $index ) {
                    if ($action == "view") {
                        return Url::to(['contact/view', 'id' => $key]);
                    }
                    if ($action == "update") {
                        return Url::to(['contact/update', 'id' => $key]);
                    }
                    if ($action == "delete") {
                        return Url::to(['contact/delete', 'id' => $key]);
                    }
                }
            ],
        ],
    ]);
    ?>
</div>
