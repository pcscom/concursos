<?php

namespace app\controllers;

use app\models\Concurso;
use app\models\TipoConcurso;
use app\models\ConcursoQuery;
use app\models\Facultad;
use app\models\AreaDepartamento;
use app\models\Categoria;
use app\models\Dedicacion;
use app\models\ConcursoAsignatura;
use app\models\Asignatura;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

?>

<div style="display:flex;flex-direction:column;padding:15px">
    <div style="display:flex;flex-direction:row;margin-bottom:20px">
        <H3 style="font-family:Helvetica">Detalle del concurso</H3>
    </div>

    <div class="px-2">
        <div style="display:flex;flex-direction:row;">
            <div style="display:flex;flex-direction:column;min-width:70%">
                <p style="font-weight:600;font-family:Helvetica">Nº Expediente</p>
            </div>        
            <div style="display:flex;flex-direction:column;min-width:30%;align-items: center;">
                <p style="font-weight:300;text-align:center;font-family:Helvetica"><?= $model->numero_expediente?></p>
            </div>
        </div>

        <div style="display:flex;flex-direction:row">
            <div style="display:flex;flex-direction:column;min-width:70%">
                <p style="font-weight:600;font-family:Helvetica">Tipo de concurso</p>
            </div>        
            <div style="display:flex;flex-direction:column;min-width:30%;align-items: center;">
                <p style="font-weight:300;text-align:center;font-family:Helvetica"><?php try{echo (TipoConcurso::find()->where(['id_tipo_concurso' => $model->id_tipo_concurso])->one()->descripcion_tipo_concurso);} catch(\Throwable $e){echo ('');} ?></p>
            </div>
        </div>

        <div style="display:flex;flex-direction:row">
            <div style="display:flex;flex-direction:column;min-width:70%">
                <p style="font-weight:600;font-family:Helvetica">Unidad académica</p>
            </div>        
            <div style="display:flex;flex-direction:column;min-width:30%;align-items: center;">
                <p style="font-weight:300;text-align:center;font-family:Helvetica"><?php try{echo (Facultad::find()->where(['id_facultad' => $model->id_facultad])->one()->nombre_facultad);} catch(\Throwable $e){echo ('');} ?></p>
            </div>
        </div>

        <div style="display:flex;flex-direction:row">
            <div style="display:flex;flex-direction:column;min-width:70%">
                <p style="font-weight:600;font-family:Helvetica">Área</p>
            </div>        
            <div style="display:flex;flex-direction:column;min-width:30%;align-items: center;">
                <p style="font-weight:300;text-align:center;font-family:Helvetica"><?php try{echo (AreaDepartamento::find()->where(['id_area_departamento' => $model->id_area_departamento])->one()->descripcion_area_departamento);} catch(\Throwable $e){echo ('');} ?></p>
            </div>
        </div>

        <div style="display:flex;flex-direction:row">
            <div style="display:flex;flex-direction:column;min-width:70%">
                <p style="font-weight:600;font-family:Helvetica">Asignatura/s</p>
            </div>    
            <?php 
                try{
                    $id_asignatura=ConcursoAsignatura::find()->where(['id_concurso' => $model->id_concurso])->one()->id_asignatura;
                    $asignatura=Asignatura::find()->where(['id_asignatura' => $id_asignatura])->one()->descripcion_asignatura;
                } 
                catch(\Throwable $e){
                    $asignatura='';
                }
            ?>    
            <div style="display:flex;flex-direction:column;min-width:30%;align-items: center;">
                <p style="font-weight:300;text-align:center;font-family:Helvetica"><?= $asignatura ?></p>
            </div>
        </div>
        
        <?php if($model->comentario): ?>
        <div style="display:flex;flex-direction:row">
            <div style="display:flex;flex-direction:column;min-width:70%">
                <p style="font-weight:600;font-family:Helvetica">Comentario adicional al grupo de adignaturas</p>
            </div>        
            <div style="display:flex;flex-direction:column;min-width:30%;align-items: center;">
                <p style="font-weight:300;text-align:center;font-family:Helvetica"><?= $model->comentario?></p>
            </div>
        </div>
        <?php endif ?>
        
        <div style="display:flex;flex-direction:row">
            <div style="display:flex;flex-direction:column;min-width:70%">
                <p style="font-weight:600;font-family:Helvetica">Categoría</p>
            </div>        
            <div style="display:flex;flex-direction:column;min-width:30%;align-items: center;">
                <p style="font-weight:300;text-align:center;font-family:Helvetica"><?php try{echo (Categoria::find()->where(['id_categoria' => $model->id_categoria])->one()->descripcion_categoria);} catch(\Throwable $e){echo ('');} ?></p>
            </div>
        </div>

        <div style="display:flex;flex-direction:row">
            <div style="display:flex;flex-direction:column;min-width:70%">
                <p style="font-weight:600;font-family:Helvetica">Dedicación</p>
            </div>        
            <div style="display:flex;flex-direction:column;min-width:30%;align-items: center;">
                <p style="font-weight:300;text-align:center;font-family:Helvetica"><?php try{echo (Dedicacion::find()->where(['id_dedicacion' => $model->id_dedicacion])->one()->descripcion_dedicacion);} catch(\Throwable $e){echo ('');} ?></p>
            </div>
        </div>

        <div style="display:flex;flex-direction:row">
            <div style="display:flex;flex-direction:column;min-width:70%">
                <p style="font-weight:600;font-family:Helvetica">Cantidad de cargos</p>
            </div>        
            <div style="display:flex;flex-direction:column;min-width:30%;align-items: center;">
                <p style="font-weight:300;text-align:center;font-family:Helvetica"><?= $model->cantidad_de_puestos?></p>
            </div>
        </div>

        <div style="display:flex;flex-direction:row">
            <div style="display:flex;flex-direction:column;min-width:70%">
                <p style="font-weight:600;font-family:Helvetica">Período de inscripción</p>
            </div>        
            <div style="display:flex;flex-direction:column;min-width:30%;align-items: center;">
                <p style="font-weight:300;text-align:center;font-family:Helvetica"></p>
            </div>
        </div>

        <div style="display:flex;flex-direction:row;align-items: center;margin-left:10px">
            <p style="font-weight:600;font-family:Helvetica;font-size: 15px;margin: 0;">Inicio inscripción</p>
            <p style="font-weight:300;text-align:center;font-family:Helvetica;margin:0;margin-left: 10px;"><?= $model->fecha_inicio_inscripcion?></p>
        </div>

        <div style="display:flex;flex-direction:row;align-items: center;margin-left:10px">
        <p style="font-weight:600;font-family:Helvetica;font-size: 15px;margin: 0;">Fin inscripción</p>
        <p style="font-weight:300;text-align:center;font-family:Helvetica;margin:0;margin-left: 10px;"><?= $model->fecha_fin_inscripcion?></p>
    </div>

    </div>

    <div style="display:flex;flex-direction:row;margin-top:20px;margin-bottom:0;font-weight:600">
        <p style="font-family:Helvetica;font-size: 12px;margin-bottom:0">Informes y confirmación de inscripción</p>
    </div>

    <hr style="border-bottom: 1px dotted gray;margin-top:4px;margin-bottom:4px;">

    <div style="display:flex;flex-direction:row;margin-top:10px">
        <p style="font-family:Helvetica;font-weight:200;font-size: 10px;">Dirección de Concursos Docentes, Pabellón Central - Avenida San Martín 4453 - Ciudad Autónoma de Buenos Aires</p>
    </div>
</div>