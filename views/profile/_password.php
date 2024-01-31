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
        <div class="col">
            <div class="row span6 justify-content-center">
                <h6 class=" ml-2 mr-1" style="height:20px;">Contraseña actual:</h6><h6 class="mt-1" style="color:#d43a06;height:20px;">*</h6>
                <?= $form->field($pass, 'oldpass')->label(false)->textInput()->passwordInput(['id' => 'oldpass','readonly' => false, 'style'=>"width:200px;font-size:15px;border:none;background:none;height:20px;border-bottom:solid 1px;"])?>
                <label class="container" style="text-align:right;margin-right:0px;margin-left:5px;margin-top:1px;width:15px;height:15px;">
                    <input id="reveal-oldpass" type="checkbox" checked="unchecked">
                    <span class="checkmark" ></span>
                </label>
            </div>
            <div class="row justify-content-center">
                <h6 class=" ml-2 mr-1" style="height:20px;">Contraseña nueva:</h6><h6 class="mt-1" style="color:#d43a06;height:20px;">*</h6>
                <?= $form->field($pass, 'newpass')->label(false)->textInput()->passwordInput(['id' => 'newpass','readonly' => false, 'style'=>"width:200px;font-size:15px;border:none;background:none;height:20px;border-bottom:solid 1px;"])?>
                <label class="container" style="text-align:right;margin-right:0px;margin-left:5px;margin-top:1px;width:15px;height:15px;">
                    <input id="reveal-newpass" type="checkbox" checked="unchecked">
                    <span class="checkmark" ></span>
                </label>
            </div>
            <div class="row justify-content-center">
                <h6 class=" ml-2 mr-1" style="height:20px;">Repetir contraseña:</h6><h6 class="mt-1" style="color:#d43a06;height:20px;">*</h6>
                <?= $form->field($pass, 'newpassagain')->label(false)->textInput()->passwordInput(['id' => 'newpassagain','readonly' => false, 'style'=>"width:192px;font-size:15px;border:none;background:none;height:20px;border-bottom:solid 1px;"])?>
                <label class="container" style="text-align:right;margin-right:0px;margin-left:5px;margin-top:1px;width:15px;height:15px;">
                    <input id="reveal-newpassagain" type="checkbox" checked="unchecked">
                    <span class="checkmark" ></span>
                </label>
            </div>           
        </div>
    </div>

    <div class="form-group" style="text-align: center">
        <?= Html::submitButton('Guardar', ['class' => 'btn', 'style' => 'background-color: #40BB97;']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

