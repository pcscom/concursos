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
use yii\helpers\Url;

?>

<div style="display:flex;flex-direction:column;padding:15px">
    <div style="display:flex;flex-direction:row;margin-bottom:20px">
        <H3 style="font-family:Helvetica">Formulario de inscripción</H3>
    </div>

    <div style="display:flex;flex-direction:row;margin-top:20px;margin-bottom:0;font-weight:600">
        <H5 style="font-family:Helvetica;margin-bottom:0">Descargue su formulario para confirmar que sus datos son correctos.</H5>
    </div>

    <div style="align-content:center;justify-content:center;display:flex;flex-direction:row;margin-top:20px;margin-bottom:0;font-weight:600">
        <button class="my-2" style="
            border:none;
            color:black;
            border-radius:5px;
            width: 120px;
            height: 50px;
            background-color: #ccc;
            font-weight: 600;
            font-size: 12px;"
        >
            <a href='concurso/descargar?ruta=<?=$file?>' style="text-decoration: none;" download><H5 style="font-family:Helvetica;margin-bottom:0">Descargar</H5></a>
        </button>
    </div>

    <div class="modal-footer" style="justify-content:end;display:flex;flex-direction:row;margin-top:20px;margin-bottom:0;font-weight:600">
        <a href="profile" style="text-decoration: none;"> 
            <button
                value=<?=$id?>
                class="btn my-2" style="
                border:none;
                color:white;
                border-radius:5px;
                width: 170px;
                height: 40px;
                background-color: #D2C60C;
                font-weight: 600;
                font-size: 12px;"
            >
            <H5 style="font-family:Helvetica;margin-bottom:0">Editar perfil</H5>
            </button>
        </a>

        <button
            id="confirmar"
            value=<?=$id?>
            class="btn my-2 confirmar" style="
            border:none;
            color:white;
            border-radius:5px;
            width: 150px;
            height: 40px;
            background-color: #40BB97;
            font-weight: 600;
            font-size: 12px;"
        >
            <H5 style="font-family:Helvetica;margin-bottom:0">Confirmar</H5>
        </button>
    </div>
</div>


<script>
  var boton = document.getElementById('confirmar');
  boton.addEventListener('click', function() {
    var concursoid = $(this).attr('value');
    event.preventDefault();
    if(concursoid){
        $.ajax({
        type: 'POST',
        url: 'concurso/confirmar',
        data: { id: concursoid },
        });
    }
    location.reload();

  });
</script>