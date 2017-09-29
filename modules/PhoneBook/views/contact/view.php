<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\PhoneBook\models\Contact */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Phone Book', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'fname',
            'created_at',
            'updated_at',
        ],
    ])
    ?>
    <h3>Phone list </h3>   
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}',
        'columns' => [
            'number',
            [
                'label' => 'type',
                'value' => function($data) {
                    return $data->type == 1 ? "Official" : "Personal";
                },
            ],
            'created_at',
        ],
    ])
    ;
    ?>
</div>
