<?php
/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\helpers\Url;


$this->title = 'Concursos';
?>
<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>

<style>
    main{
        background-color:#EEE8E7;
        background-repeat: no-repeat;
        background-size: cover;
        width:100%;
        height:650px;
    }

    .color-btn{
        background-color: #D9D9D9;
    }
    .color-btn.active {
        background-color: #BAB8B8;
    }
    .button{
        justify-content: center;
        align-items: center;
        display: flex;        
        margin-top: 5px;
        height: 35px;
        width: 300px;
        border-radius: 5px;
    }
    .icon-container {
        display: inline-block;
        background-color: #D9D9D9;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 50%;
        padding: 12px;
    }
</style>
<div class="site-index">
    <div style="display:flex;flex-direction:column;justify-content:center;align-items:center">
        <div class="card my-4" style="padding:10px;border-radius:none;border:none;background-color:#F3F4F6;width:934px;align-items:center;justify-content:center">
            <p style="margin:0;font-family:Helvetica"><b>Aviso:</b> A través de esta opción Usted podrá individualizar toda la documentación que será requerida en el momento de hacer efectiva su inscripción a un concurso en la Unidad Académica correspondiente del concurso. Al pié de la pantalla encontrará un link para descargar el formulario especificado.</p>
        </div>
        <div class="my-4" style="width:934px;justify-content:start">
        <H2 class="my-4">Documentación a presentar</H2>
        <H6 class="my-4">Detalle de la documentación a presentar</H6>
        <p class="my-4"style="font-weight: 100;">Deberá presentar toda la documentación de manera electrónica, a través de la plataforma TAD-UBA https://tramitesadistancia.uba.ar/ , para confirmar la inscripción. Debiendo presentar todas las hojas del formulario, contengan o no información del aspirante.</p>
        </div>

        <a href="<?=Url::to(['attachments/Concursos_Aspirantes-Preinscripcion.docx'])?>" download
            style="background-color:#40BB97;padding:10px;border-radius:5px;color:white;font-weight:500;text-decoration: none;">
                Descargar el formulario
            
        </a>
    </div>
</div>

