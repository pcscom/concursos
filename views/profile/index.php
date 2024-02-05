<?php
use Da\User\Widget\ConnectWidget;
use yii\helpers\VarDumper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use Da\User\Model\Profile;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use app\models\Concurso;
use app\models\TipoConcurso;
use app\models\ConcursoQuery;
use app\models\Facultad;
use app\models\AreaDepartamento;
use app\models\Categoria;
use app\models\Dedicacion;
use app\models\Designacion;
use app\models\ConcursoAsignatura;
use app\models\Asignatura;
use app\models\Trato;
use app\models\CargosActuales;

/** @var yii\web\View $this */
/** @var app\models\ProfileQuery $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
Yii::$app->params['bsDependencyEnabled'] = false;
$this->title = 'Profile';
?>

<style>
    .has-error .help-block {
        color: red;
    }
    .collapsible {
        background-color: #b3b8b7;
        color: white;
        cursor: pointer;
        padding: 10px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
    }

    .active, .collapsible:hover {
        background-color: #555;
    }

    .content {
        padding: 0 18px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.2s ease-out;
        background-color: white;
    }

    .obligatorio {
        color: red;
        margin-right: 2px;
        vertical-align: middle;
        line-height: 1;
    }
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
    .concurso_data{
        text-align: center;
        font-family: Helvetica;
        background-color: #ced4da;
        padding: 10px;
        font-weight: 500;
        justify-content: center;
        display: flex;
        margin: 0;
    }
    .text-danger {
        color: red;
    }
    .eliminarfila{
        border: 1px solid #5bbbbb;
        font-size: 14px;
    }
</style>

<div class="profile-index">
    <?php 
        $paises = [
            "Afganistán", "Albania", "Argelia", "Andorra", "Angola", "Antigua y Barbuda", "Argentina", "Armenia", "Australia", "Austria", 
            "Azerbaiyán", "Bahamas", "Baréin", "Bangladesh", "Barbados", "Bielorrusia", "Bélgica", "Belice", "Benín", "Bhután", "Bolivia", 
            "Bosnia y Herzegovina", "Botsuana", "Brasil", "Brunéi", "Bulgaria", "Burkina Faso", "Burundi", "Costa de Marfil", "Cabo Verde", 
            "Camboya", "Camerún", "Canadá", "República Centroafricana", "Chad", "Chile", "China", "Colombia", "Comoras", "Congo", 
            "Costa Rica", "Croacia", "Cuba", "Chipre", "Chequia", "República Democrática del Congo", "Dinamarca", "Yibuti", "Dominica", 
            "República Dominicana", "Ecuador", "Egipto", "El Salvador", "Guinea Ecuatorial", "Eritrea", "Estonia", "Esuatini", "Etiopía", 
            "Fiyi", "Finlandia", "Francia", "Gabón", "Gambia", "Georgia", "Alemania", "Ghana", "Grecia", "Granada", "Guatemala", 
            "Guinea", "Guinea-Bisáu", "Guyana", "Haití", "Santa Sede", "Honduras", "Hungría", "Islandia", "India", "Indonesia", "Irán", 
            "Irak", "Irlanda", "Israel", "Italia", "Jamaica", "Japón", "Jordania", "Kazajistán", "Kenia", "Kiribati", "Kuwait", 
            "Kirguistán", "Laos", "Letonia", "Líbano", "Lesoto", "Liberia", "Libia", "Liechtenstein", "Lituania", "Luxemburgo", 
            "Madagascar", "Malaui", "Malasia", "Maldivas", "Malí", "Malta", "Islas Marshall", "Mauritania", "Mauricio", "México", 
            "Micronesia", "Moldavia", "Mónaco", "Mongolia", "Montenegro", "Marruecos", "Mozambique", "Myanmar", "Namibia", "Nauru", 
            "Nepal", "Países Bajos", "Nueva Zelanda", "Nicaragua", "Níger", "Nigeria", "Corea del Norte", "Macedonia del Norte", 
            "Noruega", "Omán", "Pakistán", "Palau", "Estado de Palestina", "Panamá", "Papúa Nueva Guinea", "Paraguay", "Perú", 
            "Filipinas", "Polonia", "Portugal", "Catar", "Rumania", "Rusia", "Ruanda", "San Cristóbal y Nieves", "Santa Lucía", 
            "San Vicente y las Granadinas", "Samoa", "San Marino", "Santo Tomé y Príncipe", "Arabia Saudita", "Senegal", "Serbia", 
            "Seychelles", "Sierra Leona", "Singapur", "Eslovaquia", "Eslovenia", "Islas Salomón", "Somalia", "Sudáfrica", "Corea del Sur", 
            "Sudán del Sur", "España", "Sri Lanka", "Sudán", "Surinam", "Suecia", "Suiza", "Siria", "Tayikistán", "Tanzania", "Tailandia", 
            "Timor Oriental", "Togo", "Tonga", "Trinidad y Tobago", "Túnez", "Turquía", "Turkmenistán", "Tuvalu", "Uganda", "Ucrania", 
            "Emiratos Árabes Unidos", "Reino Unido", "Estados Unidos de América", "Uruguay", "Uzbekistán", "Vanuatu", "Venezuela", 
            "Vietnam", "Yemen", "Zambia", "Zimbabue"
        ];
        $sexos = ["Masculino","Femenino","X"];
    ?>

    <div class="col mx-0 px-0" style="border:transparent;">
        <div style="display:flex;flex-direction:row;min-width: 1000px;">
            <div class="mt-4" style="align-items:center;display:flex;flex-direction:column;width:200px;">
                <?= Html::img('@web/images/user/default_avatar.png', ['class' => 'mb-4','style' => 'height: 80px; width:80px;']) ?>
                
                <div class="mt-2">
                    <?php if(isset($cid) && $cid != '0'): ?>
                        <button  id="concurso" class="mt-2 btn btn-block color-btn active" style="width:150px;font-weight:600;border:none" onclick="changeColor(this)">Concurso</button>
                    <?php endif ?>
                </div>

                <button  id="perfil" class="mt-2 btn btn-block color-btn <?php echo (!(isset($cid) && $cid != '0'))?'active':''?>" style="width:150px;font-weight:600;border:none" onclick="changeColor(this)">Mis datos</button>
                <button  id="formulario" class="mt-2 btn btn-block color-btn" style="width:150px;font-weight:600;border:none" onclick="changeColor(this)">Formulario</button>
            </div>

            <!-- CONCURSO -->
            <div id="concursodiv" class="col card" style="display:<?php echo ((isset($cid) && $cid != '0'))?'flex':'none'?>;flex-direction:column;border:none;border-left: 2px solid #E9E9E9;border-radius: 0px;width:1100px;">
                <div class="card-body">
                    <?php if (isset($cid) && $cid != '0') :?>
                    <div style="display:flex;flex-direction:column;align-items: start;justify-content: center;">
      
                        <H3 class="mb-4 ">Detalle del concurso</H3>
                        <div class="mb-2" style="display:flex;flex-direction:row">
                            <div style="width:250px;display:flex;align-items: center;">
                                <p class="my-0">Nº Expediente</p>
                            </div>
                            <div class="ml-2" style="display:flex;align-items: center;">
                                <p class="concurso_data"><?= $concurso->numero_expediente?></p>
                            </div>
                        </div>
                        <div class="mb-2" style="display:flex;flex-direction:row">
                            <div style="width:250px;display:flex;align-items: center;">
                                <p class="my-0">Tipo de concurso</p>
                            </div>
                            <div class="ml-2" style="display:flex;align-items: center;">
                                <p class="concurso_data"><?php try{echo (TipoConcurso::find()->where(['id_tipo_concurso' => $concurso->id_tipo_concurso])->one()->descripcion_tipo_concurso);} catch(\Throwable $e){echo ('');} ?></p>
                            </div>
                        </div>
                        <div class="mb-2" style="display:flex;flex-direction:row">
                            <div style="width:250px;display:flex;align-items: center;">
                                <p class="my-0">Unidad académica</p>
                            </div>
                            <div class="ml-2" style="display:flex;align-items: center;">
                                <p class="concurso_data"><?php try{echo (Facultad::find()->where(['id_facultad' => $concurso->id_facultad])->one()->nombre_facultad);} catch(\Throwable $e){echo ('');} ?></p>
                            </div>
                        </div>
                        <div class="mb-2" style="display:flex;flex-direction:row">
                            <div style="width:250px;display:flex;align-items: center;">
                                <p class="my-0">Área</p>
                            </div>
                            <div class="ml-2" style="display:flex;align-items: center;">
                                <p class="concurso_data"><?php try{echo (AreaDepartamento::find()->where(['id_area_departamento' => $concurso->id_area_departamento])->andWhere(['id_facultad' => $concurso->id_facultad])->one()->descripcion_area_departamento);} catch(\Throwable $e){echo ('');} ?></p>
                            </div>
                        </div>
                        <div class="mb-2" style="display:flex;flex-direction:row">
                            <div style="width:250px;display:flex;align-items: center;">
                                <p class="my-0">Asignatura/s</p>
                            </div>
                            <div class="wrap ml-2" style="flex-wrap:wrap;max-width:700px;display:flex;align-items: center;">
                                <?php 
                                    try{
                                        $concursoAsignaturas=ConcursoAsignatura::find()->where(['id_concurso' => $concurso->id_concurso])->all();
                                        $idAsignaturaArray = [];
                                        foreach ($concursoAsignaturas as $concursoAsignatura) {
                                            if ($concursoAsignatura instanceof ConcursoAsignatura): ?> 

                                                <p class="concurso_data"><?= Asignatura::find()->where(['id_asignatura' => $concursoAsignatura->id_asignatura])->one()->descripcion_asignatura; ?></p>
                                            <?php endif;
                                        }
                                    } 
                                    catch(\Throwable $e){
                                        $asignatura='';
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="mb-2" style="display:flex;flex-direction:row">
                            <div style="width:250px;display:flex;align-items: center;">
                                <p class="my-0">Comentarios</p>
                            </div>
                            <div class="ml-2" style="display:flex;align-items: center;max-width:700px">
                                <p class="concurso_data"> <?= $concurso->comentario?> </p>
                            </div>
                        </div>
                        <div class="mb-2" style="display:flex;flex-direction:row">
                            <div style="width:250px;display:flex;align-items: center;">
                                <p class="my-0">Categoria</p>
                            </div>
                            <div class="ml-2" style="display:flex;align-items: center;max-width:700px">
                                <p class="concurso_data"> <?php try{echo (Categoria::find()->where(['id_categoria' => $concurso->id_categoria])->one()->descripcion_categoria);} catch(\Throwable $e){echo ('');} ?></p>
                            </div>
                        </div>
                        <div class="mb-2" style="display:flex;flex-direction:row">
                            <div style="width:250px;display:flex;align-items: center;">
                                <p class="my-0">Dedicación</p>
                            </div>
                            <div class="ml-2" style="display:flex;align-items: center;max-width:700px">
                                <p class="concurso_data"> <?php try{echo (Dedicacion::find()->where(['id_dedicacion' => $concurso->id_dedicacion])->one()->descripcion_dedicacion);} catch(\Throwable $e){echo ('');} ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif?>
                </div>
            </div>

            <!-- MIS DATOS -->
            <div id="perfildiv" class="col card" style="display:<?php echo (!(isset($cid) && $cid != '0'))?'flex':'none'?>;flex-direction:column;border:none;border-left: 2px solid #E9E9E9;border-radius: 0px;">
                <div class="card-body">
                    <?php $form = ActiveForm::begin(
                        [
                            'options' => ['style' => 'display: flex;flex-direction:column;height: 100%;justify-content: space-around;','enctype' => 'multipart/form-data'],
                            'action' => \Yii::$app->urlManager->createUrl(['/profile']),                     
                            'enableClientValidation' => false,
                            'enableAjaxValidation' => false,
                        ]
                    ) ?>

                    <div style="display:flex;flex-direction:column;align-items: start;justify-content: center;">
                    
                        <H3 class="mb-4">Mis datos de usuario</H3>
                        <div style="display: flex;flex-direction: row;align-items: first baseline;justify-content: center;">
                            <p class="obligatorio" >*</p>
                            <?= $form->field(
                                $dataProvider,
                                'nombre',
                                ['inputOptions' => ['class' => 'mt-4 text-center form-control','style' => 'display:inline;border-radius:10px;background-color:transparent;width:350px;border:1px solid black', 'autofocus' => 'autofocus', 'tabindex' => '1', 'placeholder' => 'Nombre']]
                            )->label(false)->textInput() ?>
                        </div>

                        <div style="display: flex;flex-direction: row;align-items: first baseline;justify-content: center;">
                            <p class="obligatorio" >*</p>
                            <?= $form->field(
                                $dataProvider,
                                'apellido',
                                ['inputOptions' => ['style' => 'border-radius:10px;background-color:transparent;width:350px;border:1px solid black', 'autofocus' => 'autofocus', 'class' => 'mt-4 text-center form-control', 'tabindex' => '1', 'placeholder' => 'Apellido']]
                            )->label(false)->textInput() ?>
                        </div>

                        <div style="display: flex;flex-direction: row;align-items: first baseline;justify-content: center;">
                            <p class="obligatorio" >*</p>
                            <?= $form->field(
                                $dataProvider,
                                'email',
                                ['inputOptions' => ['style' => 'border-radius:10px;background-color:transparent;width:350px;border:1px solid black', 'autofocus' => 'autofocus', 'class' => 'mt-4 text-center form-control', 'tabindex' => '1', 'placeholder' => 'Email']]
                            )->label(false)->textInput() ?>
                        </div>
                        <div style="display: flex;flex-direction: row;align-items: first baseline;justify-content: center;">
                            <p class="obligatorio" >*</p>
                            <?= $form->field(
                                $dataProvider,
                                'numero_celular_sms',
                                ['inputOptions' => ['style' => 'border-radius:10px;background-color:transparent;width:350px;border:1px solid black', 'autofocus' => 'autofocus', 'class' => 'mt-4 text-center form-control', 'tabindex' => '1', 'placeholder' => 'Teléfono']]
                            )->label(false)->textInput() ?>
                        </div>
                    </div>
                    <p style="font-weight:200;color:red;display: flex;width: 100%;flex-direction: row-reverse;">* campo obligatorio</p>

                </div>
            </div>

            <!-- PASSWORD CHANGE -->

            <div id="passworddiv" class="mt-4" style="align-items:center;display:<?php echo (!(isset($cid) && $cid != '0'))?'flex':'none'?>;flex-direction:column;width:700px;">
                <button  id="password" class="mt-2 btn btn-block" style="color:white;background-color:#FFC161;width:300px;font-weight:600;border:none" type="button">Cambiar contraseña</button>
                <div id='passwordcontentdiv'>             
                </div>
            </div>

            <!-- FORMULARIO -->

            <div id="formulariodiv" class="col card" style="display:none;flex-direction:column;border:none;border-left: 2px solid #E9E9E9;border-radius: 0px;">
                <div class="card-body">
                    <div style="display:flex;flex-direction:column;align-items: start;justify-content: center;">           
                        <button type="button" class="collapsible">DATOS DEL ASPIRANTE</button>
                        <div class="content" style="max-height: max-content;">
                            <div class="my-4">
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
                                            <p class="my-0"><p class="my-0">CUIL</p>
                                        </div>
                                        <div class="ml-2" style="display:flex;align-items: center;">
                                            <?= 
                                                $form->field(
                                                $dataProvider,
                                                'cuil',
                                                ['inputOptions' => ['id' => 'cuilinput' , 'autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'CUIL'], 'errorOptions' => ['class' => 'text-danger']]
                                                )->label(false)->textInput()
                                            ?>
                                        </div>
                                    </div>

                                    <div class="mt-2" style="display:flex;flex-direction:row">
                                        <div style="width:250px;display:flex;align-items: center;">
                                            <p class="my-0"><p class="my-0">Apellido</p>
                                        </div>
                                        <div class="ml-2" style="display:flex;align-items: center;">
                                            <input readOnly="true" value="<?= $dataProvider->apellido?>" type="text" style="background-color:#D9D9D9" class="input text-center form-control" placeholder="Apellido">
                                        </div>
                                    </div>

                                    <div class="mt-2" style="display:flex;flex-direction:row">
                                        <div style="width:250px;display:flex;align-items: center;">
                                            <p class="my-0"><p class="my-0">Nombre</p>
                                        </div>
                                        <div class="ml-2" style="display:flex;align-items: center;">
                                            <input readOnly="true" value="<?= $dataProvider->nombre?>" type="text" style="background-color:#D9D9D9" class="input text-center form-control" placeholder="Nombre">
                                        </div>
                                    </div>

                                    <div class="mt-2" style="display:flex;flex-direction:row">
                                        <div style="width:250px;display:flex;align-items: center;">
                                            <p class="my-0"><p class="my-0">Sexo</p>
                                        </div>
                                        <div class="ml-2" style="display:flex;align-items: center;">
                                            <select id="sexos" name="Profile[sexo]" style="display:block;width: 200px;height:35px;
                                                overflow: hidden; 
                                                white-space: nowrap;
                                                background-color: transparent;
                                                border: 1px solid black;
                                                text-align: center;">
                                                    <?php foreach ($sexos as $sexo): 
                                                        $selected_sexo = ($sexo === $dataProvider->sexo) ? 'selected' : '';
                                                        echo '<option value="' . $sexo . '" ' . $selected_sexo . '>' . $sexo . '</option>';
                                                    endforeach; ?>            
                                            </select>                                
                                        </div>
                                    </div>

                                    <div class="mt-2" style="display:flex;flex-direction:row">
                                        <div style="width:250px;display:flex;align-items: center;">
                                            <p class="my-0"><p class="my-0">Trato</p>
                                        </div>
                                        <div class="ml-2" style="display:flex;align-items: center;">
                                            <select id="tratos" name="Profile[id_trato]" style="display:block;width: 200px;height:35px;
                                                overflow: hidden; 
                                                white-space: nowrap;
                                                background-color: transparent;
                                                border: 1px solid black;
                                                text-align: center;">
                                                    <?php 
                                                        $tratos = Trato::find()->orderBy(['abreviatura_trato' => SORT_ASC])->all(); 
                                                        $selected = (Trato::find()->select('id_trato')->where(['id_trato' => $dataProvider->id_trato])->one() != null)?Trato::find()->select('id_trato')->where(['id_trato' => $dataProvider->id_trato])->one()->id_trato:'0'; 
                                                        foreach ($tratos as $trato): 
                                                            $selected_trato = ($trato->id_trato === $selected) ? 'selected' : '';
                                                            echo '<option value="' . $trato->id_trato . '" ' . $selected_trato . '>' . $trato->abreviatura_trato . '</option>';
                                                        endforeach; 
                                                    ?>            
                                            </select>                                
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
                                            <p class="my-0"><p class="my-0">Email</p>
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
                                            <p class="my-0"><p class="my-0"><span class="obligatorio">*</span>Estado civil</p>
                                        </div>
                                        <div class="ml-2" style="display:flex;align-items: center;">
                                            <?= 
                                                $form->field($dataProvider, 'estado_civil')->dropDownList([
                                                    'soltero' => 'Soltero',
                                                    'casado' => 'Casado',
                                                    'viudo' => 'Viudo',
                                                    'divorciado' => 'Divorciado',
                                                ], 
                                                ['id' => 'estadoCivil',
                                                'prompt' => 'Estado civil',
                                                'class' => 'input text-center form-control',
                                                'style' => 'width: 250px; border-radius: 0;text-align:center'
                                                ])->label(false);
                                            ?>
                                        </div>
                                    </div>
                                    <div id="nombreconyuge" class="mt-2" style="display:none;flex-direction:row">
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
                                            <p class="my-0"><p class="my-0"><span class="obligatorio">*</span>Localidad</p>
                                        </div>
                                        <div class="mt-2" style="display:flex;align-items: center;">
                                            <?= 
                                                $form->field(
                                                $dataProvider,
                                                'nacimiento_localidad',
                                                ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Localidad'], 'errorOptions' => ['class' => 'text-danger']]
                                                )->label(false)->textInput();
                                            ?>
                                        </div>
                                        <div class="mx-4" style="width:250px;display:flex;align-items: center;justify-content:center;max-width:max-content">
                                            <p class="my-0"><p class="my-0"><span class="obligatorio">*</span>Fecha de Nacimiento</p>
                                        </div>
                                        <div style="display:flex;align-items: center;">

                                            <?php 
                                                $dataProvider->nacimiento_fecha = ($dataProvider->nacimiento_fecha == null)?'':Yii::$app->formatter->asDate($dataProvider->nacimiento_fecha, 'dd/MM/yyyy') 
                                            ?>
                                            <?= $form->field($dataProvider, 'nacimiento_fecha')->widget(DatePicker::class, [
                                                'options' => ['placeholder' => 'Seleccione una fecha', 'id' => 'nacimientoFechaCalendar'],
                                                'language' => 'es',
                                                'readonly' => true, 
                                                'pluginOptions' => [
                                                    'autoclose' => true,
                                                    'format' => 'dd/mm/yyyy',
                                                    'todayHighlight' => true,
                                                ],
                                            ])->label(false);
                                            ?>  

                                        </div>
                                    </div>

                                    <div class="mt-2" style="display:flex;flex-direction:row">
                                        <div style="width:250px;display:flex;align-items: center;">
                                            <p class="my-0"><p class="my-0"><span class="obligatorio">*</span>Provincia</p>
                                        </div>

                                        <div class="ml-2" style="display:flex;align-items: center;">

                                            <?= 
                                                $form->field(
                                                    $dataProvider,
                                                    'nacimiento_expedido',
                                                    ['inputOptions' => ['id' => 'nacimientoprovinciaDropdown', 'autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Provincia'], 'errorOptions' => ['class' => 'text-danger']],
                                                    
                                                )->label(false)->dropDownList(
                                                    $provincias, 
                                                    ['prompt' => 'Provincia']
                                                );
                                            ?>

                                            <?= $form->field(
                                                $dataProvider,
                                                'nacimiento_expedido',
                                                ['inputOptions' => ['id' => 'nacimientoprovinciainput', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Provincia'], 'errorOptions' => ['class' => 'text-danger']]
                                            )->label(false)->textInput(['style' => 'display: none', 'id' => 'nacimientoprovinciaTextInput']);
                                            ?>
                                        </div>
                                        <div class="mx-4" style="width:250px;display:flex;align-items: center;justify-content:center;max-width:max-content">
                                            <p class="my-0"><p class="my-0"><span class="obligatorio">*</span>País</p>
                                        </div>
                                        <div style="display:flex;align-items: center;">
                                            <select id="nacimientopaises" name="Profile[nacimiento_pais]" style="display:block;width: 200px;height:35px;
                                            overflow: hidden; 
                                            white-space: nowrap;
                                            background-color: transparent;
                                            border: 1px solid black;
                                            text-align: center;">
                                                <?php 
                                                (($dataProvider->nacimiento_pais == '')||($dataProvider->nacimiento_pais == null)) && $dataProvider->nacimiento_pais = "Argentina";
                                                foreach ($paises as $pais): 
                                                    $selected = ($pais === $dataProvider->nacimiento_pais) ? 'selected' : '';
                                                    echo '<option value="' . $pais . '" ' . $selected . '>' . $pais . '</option>';
                                                endforeach; ?>            
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <H5 class="mt-4 mb-2"><p class="my-0">Domicilio</H5>
                                <div>
                                    <div class="ml-4" style="display:flex;flex-direction:row">
                                        <div style="width:250px;display:flex;align-items: center;">
                                            <p class="my-0"><p class="my-0"><span class="obligatorio">*</span>Calle</p>
                                        </div>
                                        <div class="mt-2" style="display:flex;align-items: center;">
                                            <?= 
                                                $form->field(
                                                $dataProvider,
                                                'domicilio_calle',
                                                ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Calle'], 'errorOptions' => ['class' => 'text-danger']]
                                                )->label(false)->textInput()
                                            ?>
                                        </div>
                                        <div class="mx-4" style="width:250px;display:flex;align-items: center;justify-content:center;max-width:max-content">
                                            <p class="my-0"><p class="my-0"><span class="obligatorio">*</span>Nº</p>
                                        </div>
                                        <div style="display:flex;align-items: center;">
                                            <?= 
                                                $form->field(
                                                $dataProvider,
                                                'domicilio_numero',
                                                ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Número'], 'errorOptions' => ['class' => 'text-danger']]
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
                                                ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Piso', 'errorOptions' => ['class' => 'text-danger']]]
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
                                                ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'style' => 'max-width:150px', 'tabindex' => '1', 'placeholder' => 'Departamento'], 'errorOptions' => ['class' => 'text-danger']]
                                                )->label(false)->textInput()
                                            ?>
                                        </div>
                                        <div class="mx-4" style="width:250px;display:flex;align-items: center;justify-content:center;max-width:max-content">
                                            <p class="my-0"><p class="my-0"><span class="obligatorio">*</span>CP</p>
                                        </div>
                                        <div style="display:flex;align-items: center;">
                                            <?= 
                                                $form->field(
                                                $dataProvider,
                                                'domicilio_codigo_postal',
                                                ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'style' => 'max-width:150px', 'tabindex' => '1', 'placeholder' => 'Código Postal'], 'errorOptions' => ['class' => 'text-danger']]
                                                )->label(false)->textInput()
                                            ?>
                                        </div>
                                    </div>

                                    <div class="ml-4" style="display:flex;flex-direction:row">
                                        <div style="width:250px;display:flex;align-items: center;">
                                            <p class="my-0"><p class="my-0"><span class="obligatorio">*</span>Localidad</p>
                                        </div>
                                        <div class="mt-2" style="display:flex;align-items: center;">
                                            <?= 
                                                $form->field(
                                                $dataProvider,
                                                'domicilio_localidad',
                                                ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Localidad'], 'errorOptions' => ['class' => 'text-danger']]
                                                )->label(false)->textInput()
                                            ?>
                                        </div>
                                        <div class="mx-4" style="width:250px;display:flex;align-items: center;justify-content:center;max-width:max-content">
                                            <p class="my-0"><p class="my-0"><span class="obligatorio">*</span>Provincia</p>
                                        </div>
                                        <div style="display:flex;align-items: center;">
                                            <?= 
                                                $form->field(
                                                    $dataProvider,
                                                    'domicilio_provincia',
                                                    ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Provincia'], 'errorOptions' => ['class' => 'text-danger']]
                                                )->label(false)->dropDownList(
                                                    $provincias,
                                                    ['placeholder' => 'Provincia', 'id' => 'domicilioprovinciaDropdown'],
                                                );
                                            ?>
                                            <?= $form->field(
                                                $dataProvider,
                                                'domicilio_provincia',
                                                ['inputOptions' => ['id' => 'domicilioprovinciainput', 'class' => 'input text-center form-control', 'tabindex' => '1', 'placeholder' => 'Provincia'], 'errorOptions' => ['class' => 'text-danger']]
                                                )->label(false)->textInput(['style' => 'display: none', 'id' => 'domicilioprovinciaTextInput']);
                                            ?>
                                        </div>
                                    </div>

                                    <div class="ml-4 mb-4" style="display:flex;flex-direction:row">
                                        <div style="width:100px;display:flex;align-items: center;">
                                            <p class="my-0"><p class="my-0"><span class="obligatorio">*</span>País</p>
                                        </div>
                                        <div class="mt-2" style="display:flex;align-items: center;max-width: 200px">
                                            <select id="domiciliopaises" name="Profile[domicilio_pais]" style="display:block;width: 200px;height:35px;
                                            overflow: hidden; 
                                            white-space: nowrap;
                                            background-color: transparent;
                                            border: 1px solid black;
                                            text-align: center;">
                                                <?php 
                                                    (($dataProvider->domicilio_pais == '')||($dataProvider->domicilio_pais == null)) && $dataProvider->domicilio_pais = "Argentina";
                                                    foreach ($paises as $pais): 
                                                    $selected = ($pais === $dataProvider->domicilio_pais) ? 'selected' : '';
                                                    echo '<option value="' . $pais . '" ' . $selected . '>' . $pais . '</option>';
                                                endforeach; ?>            
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="collapsible">CARGOS DOCENTES ACTUALES</button>
                        <div id="divcargos" class="content">
                            <div class="my-4">
                                <div>
                                    <H5 class="mt-4 mb-4"><p class="my-0">Cargos Docentes Actuales</H5>
                                    <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>Importante:</b>Solamente se puede inscribir a este concurso si su designación actual (concursada) es inferior en categoría, si es igual en categoría pero desea aumentar o disminuir su dedicación, o bien, si corresponde a una asignatura distinta a la que concursa.</H5>
                                    <div class="col mt-2">
                                        <div class="row mb-4" style="justify-content:center">
                                            <table style="max-width:90%">
                                                <thead>
                                                    <tr>
                                                    <th>Designación</th>
                                                    <th>Categoría</th>
                                                    <th>Dedicación</th>
                                                    <th>Asignatura</th>
                                                    <th>Facultad</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cargosactuales">
                                                    <?php
                                                    $cargosactuales=CargosActuales::find()->where(['user_id' => Yii::$app->user->id])->all(); 
                                                    foreach ($cargosactuales as $cargoactual): 
                                                        echo ('<tr>'.
                                                        '<td>'.$cargoactual->designacion.'</td>'.
                                                        '<td>'.$cargoactual->categoria.'</td>'.
                                                        '<td>'.$cargoactual->dedicacion.'</td>'.
                                                        '<td>'.$cargoactual->asignatura.'</td>'.
                                                        '<td>'.$cargoactual->facultad.'</td>'.
                                                        '<td><button class="eliminarfila">Eliminar</button></td>'.
                                                        '</tr>');
                                                    endforeach; 
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <!-- <div class="row">
                                                    <p style="width:150px"><b>Designación</b></p>
                                                    <select id="designaciones" style="display:block;width: 180px;height:30px;
                                                        overflow: hidden; 
                                                        white-space: nowrap;
                                                        background-color: transparent;
                                                        border: 1px solid black;
                                                        text-align: center;">
                                                        <option value="interno">Interno</option>          
                                                        <option value="externo">Externo</option>          
                                                    </select>   
                                                </div>                                         -->
                                                <div class="row">
                                                    <p style="width:150px"><b>Designación</b></p>
                                                    <select id="designaciones" style="display:block;width: 180px;height:30px;
                                                        overflow: hidden; 
                                                        white-space: nowrap;
                                                        background-color: transparent;
                                                        border: 1px solid black;
                                                        text-align: center;">
                                                        <?php 
                                                            $designaciones=Designacion::find()->orderBy(['descripcion_designacion' => SORT_ASC])->all(); 
                                                            foreach ($designaciones as $designacion): 
                                                                echo '<option value="' . $designacion->descripcion_designacion . '">' . $designacion->descripcion_designacion . '</option>';
                                                            endforeach; 
                                                        ?>              
                                                    </select> 
                                                </div>
                                                <div class="row">
                                                    <p style="width:150px"><b>Categoría</b></p>
                                                    <select id="categorias" style="display:block;width: 180px;height:30px;
                                                        overflow: hidden; 
                                                        white-space: nowrap;
                                                        background-color: transparent;
                                                        border: 1px solid black;
                                                        text-align: center;">
                                                        <?php 
                                                            $categorias=Categoria::find()->orderBy(['descripcion_categoria' => SORT_ASC])->all(); 
                                                            foreach ($categorias as $categoria): 
                                                                echo '<option value="' . $categoria->descripcion_categoria . '">' . $categoria->descripcion_categoria . '</option>';
                                                            endforeach; 
                                                        ?>              
                                                    </select>   
                                                </div>
                                                <div class="row">
                                                    <p style="width:150px"><b>Dedicación</b></p>
                                                    <select id="dedicaciones" style="display:block;width: 180px;height:30px;
                                                        overflow: hidden; 
                                                        white-space: nowrap;
                                                        background-color: transparent;
                                                        border: 1px solid black;
                                                        text-align: center;">
                                                        <?php 
                                                            $dedicaciones=Dedicacion::find()->orderBy(['descripcion_dedicacion' => SORT_ASC])->all(); 
                                                            foreach ($dedicaciones as $dedicacion): 
                                                                echo '<option value="' . $dedicacion->descripcion_dedicacion . '">' . $dedicacion->descripcion_dedicacion . '</option>';
                                                            endforeach; 
                                                        ?>              
                                                    </select> 
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="row">
                                                    <p style="width:150px"><b>Asignatura</b></p>
                                                    <input type="text" id="asignaturas" name="asignaturas" placeholder="Asignatura..." style="display:block;width: 180px;height:30px;
                                                        overflow: hidden; 
                                                        white-space: nowrap;
                                                        background-color: transparent;
                                                        border: 1px solid black;
                                                        text-align: center;">

                                                    <!-- <select id="asignaturas" style="display:block;width: 180px;height:30px;
                                                        overflow: hidden; 
                                                        white-space: nowrap;
                                                        background-color: transparent;
                                                        border: 1px solid black;
                                                        text-align: center;">
                                                        <?php 
                                                            $asignaturas=Asignatura::find()->orderBy(['descripcion_asignatura' => SORT_ASC])->all(); 
                                                            foreach ($asignaturas as $asignatura): 
                                                                echo '<option value="' . $asignatura->descripcion_asignatura . '">' . $asignatura->descripcion_asignatura . '</option>';
                                                            endforeach; 
                                                        ?>              
                                                    </select>  -->
                                                </div>                                        
                                                <div class="row">
                                                    <p style="width:150px"><b>Facultad</b></p>
                                                    <select id="facultades" style="display:block;width: 180px;height:30px;
                                                        overflow: hidden; 
                                                        white-space: nowrap;
                                                        background-color: transparent;
                                                        border: 1px solid black;
                                                        text-align: center;">
                                                        <?php 
                                                            $facultades=Facultad::find()->orderBy(['nombre_facultad' => SORT_ASC])->all(); 
                                                            foreach ($facultades as $facultad): 
                                                                echo '<option value="' . $facultad->nombre_facultad . '">' . $facultad->nombre_facultad . '</option>';
                                                            endforeach; 
                                                        ?>              
                                                    </select> 
                                                </div>
                                            </div>                                
                                        </div>
                                        <div class="row" style="justify-content: flex-end;">
                                            <button style="max-width:max-content" id="buttonagregarcargo" type="button" aria-label="Agregar otro cargo...">Agregar otro cargo...</button>
                                        </div>
                                    </div>            
                                </div>
                            </div>
                        </div>
                        <button type="button" class="collapsible">ANTECEDENTES ACADEMICOS</button>
                        <div class="content">
                            <div class="my-4">
                            <H5 class="mb-2">Antecedentes Académicos</H5>
                            <div class="mt-2">
                                <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>a. TITULOS UNIVERSITARIOS OBTENIDOS</b> (indicando Facultad, Universidad que los otorgó. Los títulos universitarios no expedidos por esta Universidad deberán presentarse legalizados. La legalización se realiza en https://tramitesadistancia.uba.ar/ opción "Legalización de Títulos para Concursos UBA”. De tratarse de títulos que no se encuentran en idioma español, deberá adjuntarse la debida traducción pública en el trámite de legalización.)</H5>

                                <?= 
                                    $form->field(
                                    $dataProvider,
                                    'titulos_obtenidos',
                                    ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1', 'errorOptions' => ['class' => 'text-danger']]]
                                    )->label(false)->textarea(['rows' => '6'])
                                ?>
                            </div>

                            <div class="mt-2">
                                <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>b. ANTECEDENTES DOCENTES E INDOLE DE LAS TAREAS DESARROLLADAS (indicando institución, período de ejercicio y naturaleza de su designación, lapso y lugar en que fueron realizados)</H5>
                                <?= 
                                    $form->field(
                                    $dataProvider,
                                    'antecedentes_docentes',
                                    ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1', 'errorOptions' => ['class' => 'text-danger']]]
                                    )->label(false)->textarea(['rows' => '6'])
                                ?>
                            </div>

                            <div class="mt-2">
                                <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>c. ANTECEDENTES CIENTÍFICOS, CONSIGNANDO LAS PUBLICACIONES (identificar a los autores, indicar editorial o revista, lugar y fecha de publicación, volumen, número y páginas) U OTROS RELACIONADOS CON LA ESPECIALIDAD  (indicando lapso y lugar en que fueron realizados).</H5>
                                <?= 
                                    $form->field(
                                    $dataProvider,
                                    'antecedentes_cientificos',
                                    ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1', 'errorOptions' => ['class' => 'text-danger']]]
                                    )->label(false)->textarea(['rows' => '6'])
                                ?>
                            </div>

                            <div class="mt-2">
                                <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>d. CURSOS DE ESPECIALIZACIÓN SEGUIDOS, CONFERENCIAS Y TRABAJOS DE INVESTIGACIÓN REALIZADOS SEAN ELLOS EDITOS O INEDITOS (indicando lapso y lugar en que fueron realizados). Si se invocasen trabajos inéditos deberá presentar un ejemplar digitalizado por el aspirante, que será agregado al momento de confirmar la inscripción en https://tramitesadistancia.uba.ar/..</H5>

                                <?= 
                                    $form->field(
                                    $dataProvider,
                                    'cursos',
                                    ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1', 'errorOptions' => ['class' => 'text-danger']]]
                                    )->label(false)->textarea(['rows' => '6'])
                                ?>
                            </div>

                            <div class="mt-2">
                                <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>e. PARTICIPACIÓN EN CONGRESOS O ACONTECIMIENTOS SIMILARES NACIONALES O INTERNACIONALES  (indicando lugar y lapso en que se realizaron y calidad de representación).</H5>
                                <?= 
                                    $form->field(
                                    $dataProvider,
                                    'congresos',
                                    ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1', 'errorOptions' => ['class' => 'text-danger']]]
                                    )->label(false)->textarea(['rows' => '6'])
                                ?>
                            </div>

                            <div class="mt-2">
                                <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>f. 1- ACTUACIÓN EN UNIVERSIDADES E INSTITUTOS NACIONALES, PROVINCIALES Y PRIVADOS REGISTRADOS EN EL PAIS O EN EL EXTERIOR  (indicando organismo o entidad, lugar y lapso) 2- CARGOS QUE DESEMPEÑO O DESEMPEÑA EN LA ADMINISTRACIÓN PÚBLICA O EN LA ACTIVIDAD PRIVADA, EN EL PAIS O EN EL EXTRANJERO (indicando organismo o entidad, lugar y lapso)</H5>
                                <?= 
                                    $form->field(
                                    $dataProvider,
                                    'actuacion_universidades',
                                    ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1', 'errorOptions' => ['class' => 'text-danger']]]
                                    )->label(false)->textarea(['rows' => '6'])
                                ?>

                            </div>

                            <div class="mt-2">
                                <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>g. FORMACIÓN DE RECURSOS HUMANOS (indicando becas de instituciones acreditadas, tesinas, tesis, residencias, maestrías, etc.)</H5>
                                <?= 
                                    $form->field(
                                    $dataProvider,
                                    'formacion_rrhh',
                                    ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1', 'errorOptions' => ['class' => 'text-danger']]]
                                    )->label(false)->textarea(['rows' => '6'])
                                ?>
                            </div>

                            <div class="mt-2">
                                <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>h. SÍNTESIS DE LOS APORTES ORIGINALES EFECTUADOS EN EL EJERCICIO DE LA ESPECIALIDAD RESPECTIVA  (indicando lapso y lugar en que fueron realizados; no se deben indicar los mencionados en apartados anteriores).</H5>
                                <?= 
                                    $form->field(
                                    $dataProvider,
                                    'sintesis_aportes',
                                    ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1', 'errorOptions' => ['class' => 'text-danger']]]
                                    )->label(false)->textarea(['rows' => '6'])
                                ?>
                            </div>

                            <div class="mt-2">
                                <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>i. SÍNTESIS DE LA ACTUACIÓN PROFESIONAL Y/O DE EXTENSIÓN UNIVERSITARIA  (indicando lapso y lugar en que fueron realizados; no se deben indicar los mencionados en apartados anteriores).</H5>
                                <?= 
                                    $form->field(
                                    $dataProvider,
                                    'sintesis_profesional',
                                    ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1', 'errorOptions' => ['class' => 'text-danger']]]
                                    )->label(false)->textarea(['rows' => '6'])
                                ?>
                            </div>

                            <div class="mt-2">
                                <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>j. OTROS ELEMENTOS DE JUICIO QUE CONSIDERE VALIOSOS  (indicando lapso y lugar en que fueron realizados; no se deben indicar los mencionados en apartados anteriores).</H5>
                                <?= 
                                    $form->field(
                                    $dataProvider,
                                    'otros_antecedentes',
                                    ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1', 'errorOptions' => ['class' => 'text-danger']]]
                                    )->label(false)->textarea(['rows' => '6'])
                                ?>
                            </div>

                            <div class="mt-2">
                                <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>k. PLAN DE LABOR DOCENTE, DE INVESTIGACIÓN CIENTÍFICA Y TECNOLÓGICA Y DE EXTENSIÓN UNIVERSITARIA QUE, EN LÍNEAS GENERALES, DESARROLLARÁ EN CASO DE OBTENER EL CARGO CONCURSADO. i.Para profesores titulares y asociados: Forma en que desarrollará la enseñanza, sus puntos de vista sobre temas básicos de su campo de conocimiento que deben transmitirse a los alumnos; la importancia relativa y ubicación de su área en el currículo de la carrera. Medios que propone para mantener actualizada la enseñanza y para llevar a la práctica los cambios que sugiere. ii.Para profesor adjunto: Sus puntos de vista sobre temas básicos de su campo del conocimiento que deben transmitirse a los alumnos; la importancia relativa y ubicación de su área en el currículo de la carrera. Medios que propone para mantener actualizada la enseñanza y para llevar a la práctica los cambios que sugiere.</H5>
                                <?= 
                                    $form->field(
                                    $dataProvider,
                                    'labor_docente',
                                    ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1', 'errorOptions' => ['class' => 'text-danger']]]
                                    )->label(false)->textarea(['rows' => '6'])
                                ?>
                            </div>

                            <div class="mt-2">
                                <H5 class="mb-2" style="font-family: Elvetica;font-size: 14px;"><b>SOLO PARA LOS CONCURSOS DE RENOVACIÓN</b> l. INFORME DE LOS PROFESORES QUE RENUEVAN SOBRE EL CUMPLIMIENTO DEL PLAN DE ACTIVIDADES DOCENTES, DE INVESTIGACIÓN Y/O EXTENSIÓN PRESENTADO EN EL CONCURSO ANTERIOR, ACOMPAÑADO DE LAS CERTIFICACIONES QUE CORRESPONDA</H5>
                                <?= 
                                    $form->field(
                                    $dataProvider,
                                    'renovacion',
                                    ['inputOptions' => ['autofocus' => 'autofocus', 'style' => 'width:100%', 'class' => 'input  form-control', 'tabindex' => '1', 'errorOptions' => ['class' => 'text-danger']]]
                                    )->label(false)->textarea(['rows' => '6'])
                                ?>
                            </div>

                            </div>
                        </div>
                        <p style="font-weight:200;color:red;display: flex;width: 100%;flex-direction: row-reverse;">* campo obligatorio</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <?php if(isset($cid) && $cid != '0'): ?>
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
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        if (content.style.maxHeight){
        content.style.maxHeight = null;
        } else {
        content.style.maxHeight = content.scrollHeight + "px";
        } 
    });
    }
</script>

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

        if (btn.id === 'concurso')
        {
            $('#concursodiv').css('display', 'flex');
            $('#formulariodiv').css('display', 'none');
            $('#perfildiv').css('display', 'none');
            $('#passworddiv').css('display', 'none');
        }
        else if (btn.id === 'perfil')
        {
            $('#formulariodiv').css('display', 'none');
            $('#concursodiv').css('display', 'none');
            $('#perfildiv').css('display', 'flex');
            $('#passworddiv').css('display', 'flex');
        }
        else
        {
            $('#concursodiv').css('display', 'none');
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
        }
    });

    $('#buttonagregarcargo').on('click', function (event) {
        event.preventDefault();
        var alturaActual = $("#divcargos").height();
        var nuevaAltura = alturaActual + 52;
        $("#divcargos").css("max-height", nuevaAltura);
     
        var nuevoDiv = $("<tr></tr>");
        var newrow="<td>"+$('#designaciones').val()+
        "</td>"+"<td>"+$('#categorias').val()+
        "</td>"+"<td>"+$('#dedicaciones').val()+"</td>"+
        "<td>"+$('#asignaturas').val()+"</td>"+
        "<td>"+$('#facultades').val()+"</td>"+
        "<td><button class='eliminarfila'>Eliminar</button></td>"
        if (($('#designaciones').val() != null)&&($('#categorias').val() != null)&&($('#dedicaciones').val() != null)&&($('#asignaturas').val() != '')&&($('#facultades').val() != null))
        {
            nuevoDiv.html(newrow);
            $('#cargosactuales').append(nuevoDiv);
            $.post("profile/agregarcargo", { designacion: $('#designaciones').val(), categoria: $('#categorias').val(), dedicacion: $('#dedicaciones').val(), asignatura: $('#asignaturas').val(), facultad: $('#facultades').val() });
        }
    });

    $(document).on("click", ".eliminarfila", function() {
        var fila = $(this).closest("tr");
        var valor_designacion = fila.find("td:eq(0)").text(); 
        var valor_categoria = fila.find("td:eq(1)").text(); 
        var valor_dedicacion = fila.find("td:eq(2)").text(); 
        var valor_asignatura = fila.find("td:eq(3)").text(); 
        var valor_facultad = fila.find("td:eq(4)").text(); 

        $.post("profile/eliminarcargo", { designacion: valor_designacion, categoria: valor_categoria, dedicacion: valor_dedicacion, asignatura: valor_asignatura, facultad: valor_facultad });

        $(this).closest("tr").remove();
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

    $('#nacimientopaises').change(function() {
        if(this.value == 'Argentina')
        {
            $('#nacimientoprovinciaTextInput').css('display', 'none');
            $('#nacimientoprovinciaDropdown').css('display', 'flex');
        }
        else{
            $('#nacimientoprovinciaTextInput').css('display', 'flex');
            $('#nacimientoprovinciaDropdown').css('display', 'none');
            $('#nacimientoprovinciaTextInput').val('');
        }
    });

    $('#domiciliopaises').change(function() {
        if(this.value == 'Argentina')
        {
            $('#domicilioprovinciaTextInput').css('display', 'none');
            $('#domicilioprovinciaDropdown').css('display', 'flex');
        }
        else{
            $('#domicilioprovinciaTextInput').css('display', 'flex');
            $('#domicilioprovinciaDropdown').css('display', 'none');
            $('#domicilioprovinciaTextInput').val('');
        }
    });
    $('#domicilioprovinciaDropdown').change(function() {
        $('#domicilioprovinciaTextInput').val($('#domicilioprovinciaDropdown').val());
    });

    $('#nacimientoprovinciaDropdown').change(function() {
        $('#nacimientoprovinciaTextInput').val($('#nacimientoprovinciaDropdown').val());
    });

    $('#estadoCivil').change(function() {
        if(($('#estadoCivil').val() == 'casado'))
        {
            $('#nombreconyuge').css('display', 'flex');
        }
        else{
            $('#nombreconyuge').css('display', 'none');
        }
    });

    $(document).ready(function() {
        if(($('#estadoCivil').val() == 'casado'))
        {
            $('#nombreconyuge').css('display', 'flex');
        }
        
        if($('#domiciliopaises').val() == 'Argentina')
        {
            $('#domicilioprovinciaTextInput').css('display', 'none');
            $('#domicilioprovinciaDropdown').css('display', 'flex');
        }
        else{
            $('#domicilioprovinciaTextInput').css('display', 'flex');
            $('#domicilioprovinciaDropdown').css('display', 'none');
            $('#domicilioprovinciaTextInput').val($('#domicilioprovinciaTextInput').val());
        }

        if($('#nacimientopaises').val() == 'Argentina')
        {
            $('#nacimientoprovinciaTextInput').css('display', 'none');
            $('#nacimientoprovinciaDropdown').css('display', 'flex');
        }
        else{
            $('#nacimientoprovinciaTextInput').css('display', 'flex');
            $('#nacimientoprovinciaDropdown').css('display', 'none');
            $('#nacimientoprovinciaTextInput').val($('#nacimientoprovinciaTextInput').val());
        }

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

