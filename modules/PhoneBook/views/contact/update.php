<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\PhoneBook\models\Contact */

$this->title = 'Update Contact: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Phone Book', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contact-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'phonesModel' => $phonesModel,
    ])
    ?>

</div>
