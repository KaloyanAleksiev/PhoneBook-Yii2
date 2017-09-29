<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\PhoneBook\models\ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contacts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Contact', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'label' => 'Full name',
                'value' => function($model) {
                    return $model->name . " " . $model->fname;
                },
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],
            [
                'label' => 'Phones Number',
                'attribute' => 'phonesCount',
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
