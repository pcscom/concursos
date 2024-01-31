<?php

use app\models\Concurso;
use app\models\TipoConcurso;
use app\models\ConcursoQuery;
use app\models\Facultad;
use app\models\AreaDepartamento;
use app\models\Categoria;
use app\models\Dedicacion;
use app\models\ConcursoAsignatura;
use app\models\Asignatura;

use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>

<div style="display:flex;justify-content:center;align-items:center;flex-direction:column">  
    <div class="row" style="display:flex;justify-content:center">
        <div class="col" style="max-width:90px">
            <div class="icon-container">                    
                <?=Html::img('@web/images/du/informes.png', ['alt' => 'atencion', 'style' => 'height: 25px;'])?>
            </div>
        </div>
        <div class="col" style="width: 600px;">
            <div class="row"><b>Informes</b></div>
            <div class="row ml-4"><b style="font-weight:200"><?=$model->informacion_inscripcion?></div>
        </div>
    </div>
    <div class="row" style="margin-top:50px;display:flex;justify-content:center">
        <div class="col" style="max-width:90px">
            <div class="icon-container">                    
                <?=Html::img('@web/images/du/email.png', ['alt' => 'email', 'style' => 'height: 25px;'])?>
            </div>
        </div>
        <div class="col" style="width: 600px;">
            <div class="row"><b>Email</b></div>
            <div class="row ml-4"><b style="font-weight:200"><?=$model->email?></div>
        </div>
    </div>
    <div class="row" style="margin-top:50px;display:flex;justify-content:center">
        <div class="col" style="max-width:90px">
            <div class="icon-container">                    
                <?=Html::img('@web/images/du/telefonos.png', ['alt' => 'email', 'style' => 'height: 25px;'])?>
            </div>
        </div>
        <div class="col" style="width: 600px;">
            <div class="row"><b>Teléfonos</b></div>
            <div class="row ml-4"><b style="font-weight:200"><?=$model->telefono?></div>
        </div>
    </div>
    <div class="row" style="margin-top:50px;display:flex;justify-content:center">
        <div class="col" style="max-width:90px">
            <div class="icon-container">                    
                <?=Html::img('@web/images/du/horario.png', ['alt' => 'email', 'style' => 'height: 25px;'])?>
            </div>
        </div>
        <div class="col" style="width: 600px;">
            <div class="row"><b>Horarios de atención</b></div>
            <div class="row ml-4"><b style="font-weight:200"><?=$model->horario?></div>
        </div>
    </div>
</div>