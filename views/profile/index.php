<?php
use Da\User\Widget\ConnectWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use Da\User\Model\Profile;
use kartik\date\DatePicker;
use kartik\file\FileInput;

/** @var yii\web\View $this */
/** @var app\models\ProfileQuery $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
Yii::$app->params['bsDependencyEnabled'] = false;
$this->title = 'Profile';
?>

<style>
    .color-btn{
        background-color: #D9D9D9;
    }
    .color-btn.active {
        background-color: #40BB97;
    }
    .input{
        background-color:transparent;
        width:250px;
        border:1px solid;
        align-items:center;
        border-radius:0px;
    }
    .delete_file{
        border-radius:5px;
        border:none;
        background-color:#eb214a;
        color:black;
    }
</style>

<div class="profile-index">

    <div class="col mx-0 px-0" style="border:transparent;">
        <div style="display:flex;flex-direction:row;min-width: 1000px;">
            <div class="mt-4" style="align-items:center;display:flex;flex-direction:column;width:200px;">
                <?= Html::img('@web/images/user/default_avatar.png', ['class' => 'mb-4','style' => 'height: 80px; width:80px;']) ?>
                <button  id="perfil" class="mt-2 btn btn-block color-btn active" style="width:150px;font-weight:600;border:none" onclick="changeColor(this)">Mis datos</button>
                <button  id="formulario" class="mt-2 btn btn-block color-btn" style="width:150px;font-weight:600;border:none" onclick="changeColor(this)">Formulario</button>
            </div>
            <div id="perfildiv" class="col card" style="display:flex;flex-direction:column;border:none;border-left: 2px solid #E9E9E9;border-radius: 0px;">
                <div class="card-body">
                    <?php $form = ActiveForm::begin(
                        [
                            'options' => ['style' => 'display: flex;flex-direction:column;height: 100%;justify-content: space-around;','enctype' => 'multipart/form-data'],
                            'action' => \Yii::$app->urlManager->createUrl(['/profile']),                     
                        ]
                    ) ?>
                    <div style="display:flex;flex-direction:column;align-items: start;justify-content: center;">
                    
                        <H3 class="mb-4">Mis datos de usuario</H3>

                        <?= $form->field(
                            $dataProvider,
                            'nombre',
                            ['inputOptions' => ['class' => 'mt-4 text-center form-control','style' => 'border-radius:10px;background-color:transparent;width:350px;border:1px solid black', 'autofocus' => 'autofocus', 'tabindex' => '1', 'placeholder' => 'Nombre']]
                        )->label(false)->textInput() ?>

                        <?= $form->field(
                            $dataProvider,
                            'apellido',
                            ['inputOptions' => ['style' => 'border-radius:10px;background-color:transparent;width:350px;border:1px solid black', 'autofocus' => 'autofocus', 'class' => 'mt-4 text-center form-control', 'tabindex' => '1', 'placeholder' => 'Apellido']]
                        )->label(false)->textInput() ?>

                        <?= $form->field(
                            $dataProvider,
                            'email',
                            ['inputOptions' => ['style' => 'border-radius:10px;background-color:transparent;width:350px;border:1px solid black', 'autofocus' => 'autofocus', 'class' => 'mt-4 text-center form-control', 'tabindex' => '1', 'placeholder' => 'Email']]
                        )->label(false)->textInput() ?>
                        <?= $form->field(
                            $dataProvider,
                            'numero_celular_sms',
                            ['inputOptions' => ['style' => 'border-radius:10px;background-color:transparent;width:350px;border:1px solid black', 'autofocus' => 'autofocus', 'class' => 'mt-4 text-center form-control', 'tabindex' => '1', 'placeholder' => 'Teléfono']]
                        )->label(false)->textInput() ?>
                    </div>
                </div>
            </div>
            <div id="passworddiv" class="mt-4" style="align-items:center;display:flex;flex-direction:column;width:300px;">
                <button  id="password" class="mt-2 btn btn-block" style="color:white;background-color:#FFC161;width:300px;font-weight:600;border:none" type="button">Cambiar contraseña</button>
                <div id='passwordcontentdiv'>             
                </div>
            </div>

            <!-- FORMULARIO -->

        
            <div id="formulariodiv" class="col card" style="display:none;flex-direction:column;border:none;border-left: 2px solid #E9E9E9;border-radius: 0px;">
                <div class="card-body">
                    <div style="display:flex;flex-direction:column;align-items: start;justify-content: center;">
                        <H3 class="mb-4">Formulario de inscripción</H3>
                        <H5 class="mb-2">Datos del Aspirante</H5>
                        <div>
                            <div style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Documento</p>
                                </div>
                                <div class="ml-2" style="display:flex;align-items: center;">
                                    <input readOnly="true" value="<?= $dataProvider->numero_documento?>" type="text" style="background-color:#D9D9D9" class="input text-center form-control" placeholder="Documento">
                                </div>
                            </div>

                            <div class="mt-2" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">CUIL</p>
                                </div>
                                <div class="ml-2" style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'cuil',
                                        ['inputOptions' => ['id' => 'cuilinput' , 'autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'CUIL']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                            </div>

                            <div class="mt-2" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Apellido</p>
                                </div>
                                <div class="ml-2" style="display:flex;align-items: center;">
                                    <input readOnly="true" value="<?= $dataProvider->apellido?>" type="text" style="background-color:#D9D9D9" class="input text-center form-control" placeholder="Apellido">
                                </div>
                            </div>

                            <div class="mt-2" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Nombre</p>
                                </div>
                                <div class="ml-2" style="display:flex;align-items: center;">
                                    <input readOnly="true" value="<?= $dataProvider->nombre?>" type="text" style="background-color:#D9D9D9" class="input text-center form-control" placeholder="Nombre">
                                </div>
                            </div>

                            <div class="mt-2" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Teléfono</p>
                                </div>
                                <div class="ml-2" style="display:flex;align-items: center;">
                                    <input readOnly="true" value="<?= $dataProvider->numero_celular_sms?>" type="text" style="background-color:#D9D9D9" class="input text-center form-control" placeholder="Teléfono">
                                </div>
                            </div>

                            <div class="mt-2" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Email</p>
                                </div>
                                <div class="ml-2" style="display:flex;align-items: center;">
                                    <input readOnly="true" value="<?= $dataProvider->email?>" type="text" style="background-color:#D9D9D9" class="input text-center form-control" placeholder="Email">
                                </div>
                            </div>
                        </div>
                        <H5 class="mt-4 mb-2">Datos Filiatorios</H5>
                        <div>
                            <div style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Estado civil</p>
                                </div>
                                <div class="ml-2" style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field($dataProvider, 'estado_civil')->dropDownList([
                                            'soltero' => 'Soltero',
                                            'casado' => 'Casado',
                                            'viudo' => 'Viudo',
                                            'divorciado' => 'Divorciado',
                                        ], 
                                        ['prompt' => 'Estado civil',
                                        'class' => 'input text-center form-control',
                                        'style' => 'width: 250px; border-radius: 0;text-align:center'
                                        ])->label(false);
                                    ?>
                                </div>
                            </div>

                            <div class="mt-2" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Nombre y Apellido del Cónyuge / Concubino</p>
                                </div>
                                <div class="ml-2" style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'conyuge',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Cónyuge / Concubino']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                            </div>

                            <div class="mt-2" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Nombre y Apellido de la Madre</p>
                                </div>
                                <div class="ml-2" style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'madre',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Nombre y Apellido de la Madre']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                            </div>

                            <div class="mt-2" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Nombre y Apellido del Padre</p>
                                </div>
                                <div class="ml-2" style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'padre',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Nombre y Apellido del Padre']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                            </div>
                            </div>
                        </div>
                        <H5 class="mt-4 mb-2">Lugar y Fecha de Nacimiento</H5>
                        <div>
                            <div class="ml-4" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Localidad</p>
                                </div>
                                <div class="mt-2" style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'nacimiento_localidad',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Localidad']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                                <div class="mx-4" style="width:250px;display:flex;align-items: center;justify-content:center;max-width:max-content">
                                    <p class="my-0">Fecha Nacimiento</p>
                                </div>
                                <div style="display:flex;align-items: center;">
                                    <?= $form->field($dataProvider, 'nacimiento_fecha')->widget(DatePicker::class, [
                                        'options' => ['placeholder' => 'Seleccione una fecha'],
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'yyyy-mm-dd',
                                            // Otros ajustes de configuración del widget DatePicker
                                        ]
                                    ])->label(false);
                                    ?>
                                </div>
                            </div>

                            <div class="mt-2" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Autoridad de expedición</p>
                                </div>
                                <div class="ml-2" style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'nacimiento_expedido',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Expedido por...']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                                <div class="mx-4" style="width:250px;display:flex;align-items: center;justify-content:center;max-width:max-content">
                                    <p class="my-0">País</p>
                                </div>
                                <div style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'nacimiento_pais',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Pais']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                            </div>
                        </div>
                        <H5 class="mt-4 mb-2">Domicilio</H5>
                        <div>
                            <div class="ml-4" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Calle</p>
                                </div>
                                <div class="mt-2" style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'domicilio_calle',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Calle']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                                <div class="mx-4" style="width:250px;display:flex;align-items: center;justify-content:center;max-width:max-content">
                                    <p class="my-0">Nº</p>
                                </div>
                                <div style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'domicilio_numero',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Número']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                            </div>

                            <div class="mt-2" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Piso</p>
                                </div>
                                <div class="ml-2" style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'domicilio_piso',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Piso']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                                <div class="mx-4" style="width:250px;display:flex;align-items: center;justify-content:center;max-width:max-content">
                                    <p class="my-0">Departamento</p>
                                </div>
                                <div style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'domicilio_departamento',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'style' => 'max-width:150px', 'tabindex' => '1', 'placeholder' => 'Departamento']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                                <div class="mx-4" style="width:250px;display:flex;align-items: center;justify-content:center;max-width:max-content">
                                    <p class="my-0">CP</p>
                                </div>
                                <div style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'domicilio_codigo_postal',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'style' => 'max-width:150px', 'tabindex' => '1', 'placeholder' => 'Código Postal']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                            </div>

                            <div class="ml-4" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">Localidad</p>
                                </div>
                                <div class="mt-2" style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'domicilio_localidad',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Localidad']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                                <div class="mx-4" style="width:250px;display:flex;align-items: center;justify-content:center;max-width:max-content">
                                    <p class="my-0">Provincia</p>
                                </div>
                                <div style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'domicilio_provincia',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Provincia']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                            </div>

                            <div class="ml-4" style="display:flex;flex-direction:row">
                                <div style="width:250px;display:flex;align-items: center;">
                                    <p class="my-0">País</p>
                                </div>
                                <div class="mt-2" style="display:flex;align-items: center;">
                                    <?= 
                                        $form->field(
                                        $dataProvider,
                                        'domicilio_pais',
                                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Pais']]
                                        )->label(false)->textInput()
                                    ?>
                                </div>
                            </div>
                        </div>

                        <H3 class="mb-4">Antecedentes</H3>
                        <div class="mt-2">
                            <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>a. TITULOS UNIVERSITARIOS OBTENIDOS</b> (indicando Facultad, Universidad que los otorgó. Los títulos universitarios no expedidos por esta Universidad deberán presentarse legalizados. La legalización se realiza en https://tramitesadistancia.uba.ar/ opción “Legalización de Títulos para Concursos UBA”. De tratarse de títulos que no se encuentran en idioma español, deberá adjuntarse la debida traducción pública en el trámite de legalización.)</H5>

                            <?= 
                                $form->field(
                                $dataProvider,
                                'titulos_obtenidos',
                                ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1']]
                                )->label(false)->textarea(['rows' => '6'])
                            ?>
                        </div>

                        <div class="mt-2">
                            <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>b. ANTECEDENTES DOCENTES E INDOLE DE LAS TAREAS DESARROLLADAS (indicando institución, período de ejercicio y naturaleza de su designación, lapso y lugar en que fueron realizados)</H5>
                            <?= 
                                $form->field(
                                $dataProvider,
                                'antecedentes_docentes',
                                ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1']]
                                )->label(false)->textarea(['rows' => '6'])
                            ?>
                        </div>

                        <div class="mt-2">
                            <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>c. ANTECEDENTES CIENTÍFICOS, CONSIGNANDO LAS PUBLICACIONES (identificar a los autores, indicar editorial o revista, lugar y fecha de publicación, volumen, número y páginas) U OTROS RELACIONADOS CON LA ESPECIALIDAD  (indicando lapso y lugar en que fueron realizados).</H5>
                            <?= 
                                $form->field(
                                $dataProvider,
                                'antecedentes_cientificos',
                                ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1']]
                                )->label(false)->textarea(['rows' => '6'])
                            ?>
                        </div>

                        <div class="mt-2">
                            <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>d. CURSOS DE ESPECIALIZACIÓN SEGUIDOS, CONFERENCIAS Y TRABAJOS DE INVESTIGACIÓN REALIZADOS SEAN ELLOS EDITOS O INEDITOS (indicando lapso y lugar en que fueron realizados). Si se invocasen trabajos inéditos deberá presentar un ejemplar digitalizado por el aspirante, que será agregado al momento de confirmar la inscripción en https://tramitesadistancia.uba.ar/..</H5>

                            <?= 
                                $form->field(
                                $dataProvider,
                                'cursos',
                                ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1']]
                                )->label(false)->textarea(['rows' => '6'])
                            ?>
                        </div>

                        <div class="mt-2">
                            <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>e. PARTICIPACIÓN EN CONGRESOS O ACONTECIMIENTOS SIMILARES NACIONALES O INTERNACIONALES  (indicando lugar y lapso en que se realizaron y calidad de representación).</H5>
                            <?= 
                                $form->field(
                                $dataProvider,
                                'congresos',
                                ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1']]
                                )->label(false)->textarea(['rows' => '6'])
                            ?>
                        </div>

                        <div class="mt-2">
                            <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>f. 1- ACTUACIÓN EN UNIVERSIDADES E INSTITUTOS NACIONALES, PROVINCIALES Y PRIVADOS REGISTRADOS EN EL PAIS O EN EL EXTERIOR  (indicando organismo o entidad, lugar y lapso) 2- CARGOS QUE DESEMPEÑO O DESEMPEÑA EN LA ADMINISTRACIÓN PÚBLICA O EN LA ACTIVIDAD PRIVADA, EN EL PAIS O EN EL EXTRANJERO (indicando organismo o entidad, lugar y lapso)</H5>
                            <?= 
                                $form->field(
                                $dataProvider,
                                'actuacion_universidades',
                                ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1']]
                                )->label(false)->textarea(['rows' => '6'])
                            ?>

                        </div>

                        <div class="mt-2">
                            <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>g. FORMACIÓN DE RECURSOS HUMANOS (indicando becas de instituciones acreditadas, tesinas, tesis, residencias, maestrías, etc.)</H5>
                            <?= 
                                $form->field(
                                $dataProvider,
                                'formacion_rrhh',
                                ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1']]
                                )->label(false)->textarea(['rows' => '6'])
                            ?>
                        </div>

                        <div class="mt-2">
                            <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>h. SÍNTESIS DE LOS APORTES ORIGINALES EFECTUADOS EN EL EJERCICIO DE LA ESPECIALIDAD RESPECTIVA  (indicando lapso y lugar en que fueron realizados; no se deben indicar los mencionados en apartados anteriores).</H5>
                            <?= 
                                $form->field(
                                $dataProvider,
                                'sintesis_aportes',
                                ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1']]
                                )->label(false)->textarea(['rows' => '6'])
                            ?>
                        </div>

                        <div class="mt-2">
                            <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>i. SÍNTESIS DE LA ACTUACIÓN PROFESIONAL Y/O DE EXTENSIÓN UNIVERSITARIA  (indicando lapso y lugar en que fueron realizados; no se deben indicar los mencionados en apartados anteriores).</H5>
                            <?= 
                                $form->field(
                                $dataProvider,
                                'sintesis_profesional',
                                ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1']]
                                )->label(false)->textarea(['rows' => '6'])
                            ?>
                        </div>

                        <div class="mt-2">
                            <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>j. OTROS ELEMENTOS DE JUICIO QUE CONSIDERE VALIOSOS  (indicando lapso y lugar en que fueron realizados; no se deben indicar los mencionados en apartados anteriores).</H5>
                            <?= 
                                $form->field(
                                $dataProvider,
                                'otros_antecedentes',
                                ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1']]
                                )->label(false)->textarea(['rows' => '6'])
                            ?>
                        </div>

                        <div class="mt-2">
                            <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>k. PLAN DE LABOR DOCENTE, DE INVESTIGACIÓN CIENTÍFICA Y TECNOLÓGICA Y DE EXTENSIÓN UNIVERSITARIA QUE, EN LÍNEAS GENERALES, DESARROLLARÁ EN CASO DE OBTENER EL CARGO CONCURSADO. i.Para profesores titulares y asociados: Forma en que desarrollará la enseñanza, sus puntos de vista sobre temas básicos de su campo de conocimiento que deben transmitirse a los alumnos; la importancia relativa y ubicación de su área en el currículo de la carrera. Medios que propone para mantener actualizada la enseñanza y para llevar a la práctica los cambios que sugiere. ii.Para profesor adjunto: Sus puntos de vista sobre temas básicos de su campo del conocimiento que deben transmitirse a los alumnos; la importancia relativa y ubicación de su área en el currículo de la carrera. Medios que propone para mantener actualizada la enseñanza y para llevar a la práctica los cambios que sugiere.</H5>
                            <?= 
                                $form->field(
                                $dataProvider,
                                'labor_docente',
                                ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1']]
                                )->label(false)->textarea(['rows' => '6'])
                            ?>
                        </div>

                        <div class="mt-2">
                            <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>SOLO PARA LOS CONCURSOS DE RENOVACIÓN</b> l. INFORME DE LOS PROFESORES QUE RENUEVAN SOBRE EL CUMPLIMIENTO DEL PLAN DE ACTIVIDADES DOCENTES, DE INVESTIGACIÓN Y/O EXTENSIÓN PRESENTADO EN EL CONCURSO ANTERIOR, ACOMPAÑADO DE LAS CERTIFICACIONES QUE CORRESPONDA</H5>
                            <?= 
                                $form->field(
                                $dataProvider,
                                'renovacion',
                                ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1']]
                                )->label(false)->textarea(['rows' => '6'])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <?php if(isset($cid) && $cid !== 0): ?>
            <?= $form->field($dataProvider, 'cid')->hiddenInput(['value' => $cid])->label(false) ?>
                <div class="mt-2" style="justify-content: space-around;display: flex;justify-content:center;width:200px">
                    <?= Html::submitButton('Confirmar Preinscripción',['class' => 'mt-4 btn btn-block', 'style'=>'width:150px;background-color:#40BB97;font-weight:600','tabindex' => '3']) ?>
                </div>
            <?php else: ?>
                <div style="justify-content: space-around;display: flex;justify-content:center;width:200px">
                    <?= Html::submitButton('Guardar',['class' => 'mt-4 btn btn-block', 'style'=>'width:150px;background-color:#40BB97;font-weight:600','tabindex' => '3']) ?>
                </div>
            <?php endif ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Atención</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Si continúa perderá los cambio no guardados.</p>
        <p style="font-weight:400">¿Estás seguro de que deseas continuar?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="confirmButton">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script>
    function changeColor(btn) {
        // obtenemos todos los botones con la clase "color-btn" onclick="changeColor(this)"
        var buttons = document.querySelectorAll('.color-btn');
        let id=$(this).attr('value');

        // removemos la clase "active" de todos los botones
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].classList.remove('active');
        }
        
        // agregamos la clase "active" al botón clickeado
        btn.classList.add('active');
        btn.classList.add('active');
        if (btn.id === 'perfil')
        {
            $('#formulariodiv').css('display', 'none');
            $('#perfildiv').css('display', 'flex');
            $('#passworddiv').css('display', 'flex');
        }
        else
        {
            $('#perfildiv').css('display', 'none');
            $('#formulariodiv').css('display', 'flex');
            $('#passworddiv').css('display', 'none');
        }

    }
</script>

<?php
    $js = <<< JS
    $('#password').on('click', function (event) {
        event.preventDefault();
        if ($("#passworddiv").is(":visible")) {
            $('#confirmationModal').modal('show');
            // $('#passwordcontentdiv').toggle(); 
        }
    });

    $('#confirmButton').on('click', function (event) {
        window.location.href = 'user/security/password'; 
    });

    $('.vermas').on('click', function (event) {
        let id=$(this).attr('value')
        event.preventDefault();
        $('#modalform').load('concurso/formulario?id='+id);
        $('#vermasModal').modal('show');
    })

    $('.delete_file').on('click', function(event) {
        event.preventDefault();
        var value = $(this).data('value');
        var escaped = $.escapeSelector(value);
        $.ajax({
            type: 'POST',
            url: 'profile/delete',
            data: { doc: value },
            success: function(response) {
                $('#' + escaped).fadeOut();
            }
        });

    });

    $(document).ready(function() {

        $("#cuilinput").on("input", function() {
            var maxLength = 12;
            if ($(this).val().length > maxLength) {
                $(this).val($(this).val().slice(0, maxLength));
            }
        });
    });
    JS;
    $this->registerJs($js);
?>

