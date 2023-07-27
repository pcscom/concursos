<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var Da\User\Model\Profile $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="profile-form" style="margin-left: auto;margin-right: auto;width: 600px;">
    <div class="card" style="border:1px solid black;border-radius:10px;padding:10px;max-width:700px">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'user_id')->textInput() ?>

        <?= $form->field($model, 'numero_documento')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'apellido')->textInput() ?>

        <?= $form->field($model, 'nombre')->textInput() ?>

        <?= $form->field($model, 'numero_legajo')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sexo')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'cuil')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'numero_celular_sms')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'proveedor_celular')->textInput(['maxlength' => true]) ?>

    </div>

    <div class="form-group" style="text-align: center">
        <?= Html::submitButton('Guardar', ['class' => 'btn', 'style' => 'background-color: #40BB97;']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
