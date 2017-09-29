<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\PhoneBook\models\Contact */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contact-form">

    <?php $form = ActiveForm::begin(['id' => 'phoneForm']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>

    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-phone"></i> Phone book</h4></div>
        <div class="panel-body">
            <?php
            DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 4, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $phonesModel[0],
                'formId' => 'phoneForm',
                'formFields' => [
                    'number',
                    'type',
                ],
            ]);
            ?>

            <div class="container-items"><!-- widgetContainer -->
<?php foreach ($phonesModel as $i => $phoneModel): ?>
                    <div class="item panel panel-default"><!-- widgetBody -->
                        <div class="panel-heading">
                            <h3 class="panel-title pull-left">Numbers</h3>
                            <div class="pull-right">
                                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?php
                            // necessary for update action.
                            if (!$phoneModel->isNewRecord) {
                                echo Html::activeHiddenInput($phoneModel, "[{$i}]id");
                            }
                            ?>

                            <div class="row">
                                <div class="col-sm-6">
    <?= $form->field($phoneModel, "[{$i}]number")->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-sm-6">


    <?= $form->field($phoneModel, "[{$i}]type")->dropDownList(['0' => 'Personal', '1' => 'Official']) ?>
                                </div>
                            </div><!-- .row -->
                        </div>
                    </div>
            <?php endforeach; ?>
            </div>
<?php DynamicFormWidget::end(); ?>
        </div>
    </div>

    <div class="form-group">
<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
