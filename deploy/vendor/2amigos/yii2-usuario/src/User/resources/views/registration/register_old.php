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

/**
 * @var yii\web\View                   $this
 * @var \Da\User\Form\RegistrationForm $model
 * @var \Da\User\Model\User            $user
 * @var \Da\User\Module                $module
 */

?>

<?= $this->render('/shared/_alert', ['module' => Yii::$app->getModule('user')]) ?>
<style>
    main{
        background-color:#EEE8E7;
        background-repeat: no-repeat;
        background-size: cover;
        width:100%;
        height:750px;
    }
    .triangle {
        width: 0;
        height: 0;
        border-top: 750px solid transparent;
        border-left: 800px solid white;
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
</style>
<div class="triangle"></div>

<div class="row" style="margin-top:100px">
    <div class="col" style="justify-content:center">
        <div class="row" style="max-width:fit-content;margin: 0 auto;">
            <div class="col mx-0 px-0" style="border:transparent">
                <div class="card" style="justify-content:center;align-items:center;border-radius:0;background-color:rgba(56, 56, 56, 0.1);width:500px;height:600px;width:400px">
                    <H1>¡Bienvenido!</H1>
                    <H3 style="font-weight: 400;">Registrarse</H3>
                    <p class="text-center" style="position:absolute;bottom:5px">
                    <a href="<?=Url::to(['/user/security/login'])?>" style="text-decoration: none;color:black;font-weight: 200;font-family: Helvetica;">Ya tengo una cuenta creada <b style="font-weight: 600;font-family: Helvetica;">Iniciar Sesión</b></a>
                    </p>
                </div>
            </div>
            <div class="col mx-0 px-0">
                <div class="card" style="border-radius:0;height:600px;width:400px;">
                    <div class="card-body">

                        <?php $form = ActiveForm::begin(
                            [
                                'id' => $model->formName(),
                                'enableAjaxValidation' => true,
                                'enableClientValidation' => false,
                                'options' => ['style' => 'display: flex;flex-direction:column;height: 100%;justify-content: space-around;'],

                            ]
                        ); ?>

                        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'username') ?>

                        <?php if ($module->generatePasswords === false): ?>
                            <?= $form->field($model, 'password')->passwordInput() ?>
                        <?php endif ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($module->enableEmailConfirmation): ?>
            <p class="text-center">
                <?= Html::a(
                    Yii::t('usuario', 'Didn\'t receive confirmation message?'),
                    ['/user/registration/resend']
                ) ?>
            </p>
        <?php endif ?>


    </div>
</div>
