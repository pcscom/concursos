<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View               $this
 * @var yii\widgets\ActiveForm     $form
 * @var \Da\User\Form\RecoveryForm $model
 */

?>
<style>
    main{
        background-color:#EEE8E7;
        background-repeat: no-repeat;
        background-size: cover;
        width:100%;
        height:650px;
    }
    .triangle {
        width: 0;
        height: 0;
        border-top: 650px solid transparent;
        border-left: 1100px solid white;
        position: absolute;
        left: 0;
    }
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    /* Firefox */
    input[type=number] {
    -moz-appearance: textfield;
    }
    .card{
        border:none;
    }
            /* Estilos para el modal */
            .modal {
            /* display: block; */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 350px;
            height: 350px;
        }

        /* Estilos para el botón que abre el modal */
        .open-modal-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
</style>
<div class="triangle"></div>
<div class="row" style="margin-top:100px;">
    <div class="col" style="justify-content:center">
        <div class="row" style="max-width:fit-content;margin: 0 auto;">
            <div class="col mx-0 px-0" style="border:transparent">
                <div class="card" style="justify-content:center;align-items:center;border-radius:0;background-color:rgba(56, 56, 56, 0.1);width:500px;height:300px;width:400px">
                    <H1>¡Bienvenido!</H1>
                    <p><?= $confirmationModal?'block':'none'?></p>
                    <H3 style="font-weight: 400;">Iniciar Sesión</H3>
                    <p class="text-center" style="position:absolute;bottom:5px">
                    <a href="<?=Url::to(['/user/registration/register'])?>" style="text-decoration: none;color:black;font-weight: 200;font-family: Helvetica;">¿No está registrado? <b style="font-weight: 600;font-family: Helvetica;">REGISTRARSE</b></a>
                    </p>
                </div>
            </div>
            <div class="col mx-0 px-0" style="border:transparent">
                <div class="card" style="border-radius:0;height:300px;width:400px">
                    <div class="card-body" style="display: flex;justify-content: center;">
                        <div style="display:flex;flex-direction:column;align-items: center;justify-content: center;">
                            <p style="max-width:300px"><b>Ingrese su dirección de correo registrado</b></p>

                                <?php $form = ActiveForm::begin(
                                    [
                                        'id' => $model->formName(),
                                        'enableAjaxValidation' => true,
                                        'enableClientValidation' => false,
                                    ]
                                ); ?>

                                <?= $form->field($model, 'email')->textInput(['autofocus' => true])->label(false)?>
                                <div style="justify-content: center;display: flex;">
                                    <?= Html::submitButton(Yii::t('usuario', 'Aceptar'), ['class' => 'btn btn-block', 'style'=>'color:white;width:150px;background-color:#40BB97;font-weight:600']) ?><br>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div id="confirmationModal" class="modal" style="display:<?= $confirmationModal?'block':'none' ?>">
                        <div class="modal-content">
                            <span class="close text-end confirmButton" id="confirmButton">&times;</span>
                            <div style="display:flex;flex-direction:column;justify-content:center;align-items:center;flex:1">
                                <?= Html::img('@web/images/mail.png', ['style' => 'height:50px']) ?>
                                <H4 class="mt-2" style="color:#40BB97">Confirmación</H4>
                                <p class="mt-2" style="font: size 10px;">Te enviamos un mail para verificar tu dirección de correo y que continúes con el cambio de contraseña.</p>
                                <div class="mt-4">
                                    <button class = "btn btn-block confirmButton" style="color:white;width:120px;background-color:#40BB97;font-weight:600" >Aceptar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>


<?php
    $js = <<< JS

    $('.confirmButton').on('click', function (event) {
        document.getElementById("confirmationModal").style.display = "none";
    });

    JS;
    $this->registerJs($js);
?>