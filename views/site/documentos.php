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
            <p style="margin:0;font-family:Helvetica"><b>Aviso:</b> A través de esta opción Usted podrá individualizar toda la documentación que será requerida en el momento de hacer efectiva su inscripción a un concurso.</p>
        </div>
        <div class="my-4" style="width:934px;justify-content:start">
        <H2 class="my-4">Documentación a presentar</H2>
        <p class="my-4"style="font-weight: 200;">Esta es la documentación que deberá cargar al momento de hacer la preinscripción. Luego de completar la preinscripción recibirá un certificado de preinscripción que deberá presentar a través de la plataforma TAD-UBA <a target="_blank" href="https://tramitesadistancia.uba.ar/">https://tramitesadistancia.uba.ar/</a>, para confirmar la inscripción. Debiendo presentar todas las hojas del formulario, contengan o no información del aspirante. La información ingresada se guardará en su perfil, podrá editarla, desde "mi perfil" o a la hora de hacer la preinscripción.</p>
        </div>
        <div style="width:934px;justify-content:start">
            <H6 class="my-1">A. TITULOS UNIVERSITARIOS OBTENIDOS</H6>
            <H6 class="my-1">B. ANTECEDENTES DOCENTES E INDOLE DE LAS TAREAS DESARROLLADAS</H6>
            <H6 class="my-1">C. ANTECEDENTES CIENTÍFICOS, CONSIGNANDO LAS PUBLICACIONES</H6>
            <H6 class="my-1">D. CURSOS DE ESPECIALIZACIÓN SEGUIDOS, CONFERENCIAS Y TRABAJOS DE INVESTIGACIÓN REALIZADOS SEAN ELLOS EDITOS O INEDITOS</H6>
            <H6 class="my-1">E. PARTICIPACIÓN EN CONGRESOS O ACONTECIMIENTOS SIMILARES NACIONALES O INTERNACIONALES</H6>
            <H6 class="my-1">F. 1. ACTUACIÓN EN UNIVERSIDADES E INSTITUTOS NACIONALES, PROVINCIALES Y PRIVADOS REGISTRADOS EN EL PAIS O EN EL EXTERIOR 2. CARGOS QUE DESEMPEÑO O DESEMPEÑA EN LA ADMINISTRACIÓN PÚBLICA O EN LA ACTIVIDAD PRIVADA, EN EL PAIS O EN EL EXTRANJERO</H6>
            <H6 class="my-1">G. FORMACIÓN DE RECURSOS HUMANOS</H6>
            <H6 class="my-1">H. SÍNTESIS DE LOS APORTES ORIGINALES EFECTUADOS EN EL EJERCICIO DE LA ESPECIALIDAD RESPECTIVA</H6>
            <H6 class="my-1">I. SÍNTESIS DE LA ACTUACIÓN PROFESIONAL Y/O DE EXTENSIÓN UNIVERSITARIA</H6>
            <H6 class="my-1">J. OTROS ELEMENTOS DE JUICIO QUE CONSIDERE VALIOSOS</H6>
            <H6 class="my-1">K. PLAN DE LABOR DOCENTE, DE INVESTIGACIÓN CIENTÍFICA Y TECNOLÓGICA Y DE EXTENSIÓN UNIVERSITARIA QUE, EN LÍNEAS GENERALES, DESARROLLARÁ EN CASO DE OBTENER EL CARGO CONCURSADO.</H6>
            <H6 class="my-1">L. SOLO PARA LOS CONCURSOS DE RENOVACIÓN: INFORME DE LOS PROFESORES QUE RENUEVAN SOBRE EL CUMPLIMIENTO DEL PLAN DE ACTIVIDADES DOCENTES, DE INVESTIGACIÓN Y/O EXTENSIÓN PRESENTADO EN EL CONCURSO ANTERIOR, ACOMPAÑADO DE LAS CERTIFICACIONES QUE CORRESPONDA.</H6>
        </div>

        <!-- <a href="<?=Url::to(['attachments/Concursos_Aspirantes-Preinscripcion.docx'])?>" download
            style="background-color:#40BB97;padding:10px;border-radius:5px;color:white;font-weight:500;text-decoration: none;">
                Descargar el formulario
            
        </a> -->
    </div>
</div>

