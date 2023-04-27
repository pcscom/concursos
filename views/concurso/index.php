<?php
/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

use kartik\select2\Select2;

use app\models\Concurso;
use app\models\ConcursoQuery;
use app\models\Facultad;
use app\models\AreaDepartamento;
use app\models\Categoria;
use app\models\ConcursoAsignatura;
use app\models\Asignatura;
use app\models\Dedicacion;

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
    a, a:visited, a:hover, a:active {
        color: inherit;
    }

    .boton {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #F5F5F5;
        color: black;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

</style>

<div class="site-index">
    <div style="display:flex;flex-direction:column;justify-content:center;align-items:center">
        <div class="card my-4" style="padding:10px;border-radius:none;border:none;background-color:#F3F4F6;width:934px;align-items:center;justify-content:center">
            <p style="margin:0;font-family:Helvetica"><b>Aviso:</b> A través de esta pantalla usted podrá visualizar los concursos que fueron aprobados por el Consejo Superior de la UBA (publicados) hasta el momento en que se cierra la Nómina de Inscriptos.</p>
        </div>
        <div class="pt-2" style="display: flex;flex-direction: column;width: 934px;">
            <H1 style="font-weight:700;margin-bottom:0">Llamados a Concursos</H1>
            <H1 style="font-weight:700;margin-bottom:0">Aprobados por el</H1>
            <H1 style="font-weight:700;">Consejo Superior</H1>            
        </div>

        <!-- FILTROS -->
        <div style="display: flex;flex-direction: column;width: 934px;">
            <div class="pt-2" style="display: flex;flex-direction:row-reverse;width: 934px;">
                <p style="font-size:18px;font-weight:400">Establecer opciones de filtrado</p>
            </div>
            <div class="pt-4" style="display: flex;flex-direction:row-reverse;width: 934px;">
                <select id="facultades" name="facultades" style="display:block;width: 400px; 
                overflow: hidden; 
                white-space: nowrap;">
                    <option value="0"></option>
                    <?php foreach ($facultad as $fac): ?>
                        <option value="<?= $fac['id_facultad'] ?>">
                            <?= Facultad::find()->select('nombre_facultad')
                            ->where(['id_facultad' => $fac['id_facultad']])
                            ->one()->nombre_facultad; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="mx-4" style="font-size:18px;font-weight:400;height:15px">Unidad Académica</p>
            </div>

            <div class="pt-4" style="display: flex;flex-direction:row-reverse;width: 934px;">
                <select class="areas" id="areas" name="areas" style="width: 400px; 
                overflow: hidden; 
                white-space: nowrap;"
                disabled>
                </select>
                <p class="mx-4 areas" style="font-size:18px;font-weight:400;height:15px"><?php try{echo (AreaDepartamento::find()->where(['id_area_departamento' => $concurso['id_area_departamento']])->one()->descripcion_area_departamento);} catch(\Throwable $e){echo ('');} ?></p>
            </div>

            <div class="mt-2" style="display: flex;flex-direction:row-reverse;width: 100%;">
                <a id="filtro" href="#" style="text-decoration:none">
                    <div class="btn btn-block" style="width:120px;background-color:#40BB97;font-weight:600;font-size:12px">
                        FILTRAR
                    </div>
                </a>
            </div>
        </div>

        <!-- BOTONES -->
        <div class="pt-4" style="display:flex;flex-wrap:wrap;width: 934px;flex-direction:row;">
            <?php 
                $botones=Concurso::find()->where(['like','id_facultad',$ua,false])->andWhere(['like','id_categoria',$ar,false])->groupBy('id_facultad')->all();
                foreach ($botones as $boton): 
            ?>
                <?php $href="concurso?ua=".$boton['id_facultad']."&ar=".$ar ?>
                <a href="<?=$href?>" style="cursor:pointer;text-decoration:none;width:45%">
                    <div class="py-2" style="display:flex;flex-direction:row;">
                        <div class="boton">
                            <span><?php echo(Concurso::find()->where(['like','id_facultad',$boton['id_facultad'],false])->andWhere(['like','id_categoria',$boton['id_categoria'],false])->count())?></span>
                        </div>
                        <div style="display:flex;flex-direction:column;justify-content: center;">
                            <h5 class="mx-4 my-0" style="display: flex;flex-direction: column;justify-content: center;"><?php echo(Facultad::find()->where(['id_facultad' => $boton['id_facultad']])->one()->nombre_facultad)?></h5>
                            <?php if($ua=='%'): ?>
                            <p style="font-weight: 200;font-size: 15px;" class="mx-4 py-0 my-0">Haga click para ver</p>
                            <p style="font-weight: 200;font-size: 15px;" class="mx-4 py-0 my-0">sus concursos</p>
                            <?php endif ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <?php if($ua!='%'): ?>
            <!-- GRID -->
            <div class="pt-4" style="display:flex;width: 934px;flex-direction:column;">
            <table>
                <tr>
                    <th style="text-align: center;vertical-align: middle;width:100px">Área</td>
                    <th style="text-align: center;vertical-align: middle;width:100px">Asignatura/s</td>
                    <th style="text-align: center;vertical-align: middle;width:100px">Categoría</td>
                    <th style="text-align: center;vertical-align: middle;width:100px">Dedicación</td>
                    <th style="text-align: center;vertical-align: middle;width:70px">Cargos</td>
                    <th style="text-align: center;vertical-align: middle;width:150px">Período de Inscripción</td>
                    <th style="text-align: center;vertical-align: middle;width:100px">Nº Expediente</td>
                    <th style="text-align: center;vertical-align: middle;width:80px"></td>
                </tr>
                <?php 
                    $concursos=Concurso::find()->where(['like','id_facultad',$ua,false])->andWhere(['like','id_categoria',$ar,false])->groupBy('id_facultad')->all();
                    foreach ($concursos as $concurso): 
                ?>
                    <tr>
                        <td style="text-align: center;vertical-align: middle;width:100px"><?php try{echo (AreaDepartamento::find()->where(['id_area_departamento' => $concurso['id_area_departamento']])->one()->descripcion_area_departamento);} catch(\Throwable $e){echo ('');} ?></th>
                        <?php 
                            try{
                                $id_asignatura=ConcursoAsignatura::find()->where(['id_concurso' => $concurso['id_concurso']])->one()->id_asignatura;
                                $asignatura=Asignatura::find()->where(['id_asignatura' => $id_asignatura])->one()->descripcion_asignatura;
                            } 
                            catch(\Throwable $e){
                                $asignatura='';
                            }
                        ?>
                        <td style="text-align: center;vertical-align: middle;width:150px"><?=$asignatura?></th>
                        <td style="text-align: center;vertical-align: middle;width:100px"><?php try{echo (Categoria::find()->where(['id_categoria' => $concurso['id_categoria']])->one()->descripcion_categoria);} catch(\Throwable $e){echo ('');}?></th>
                        <td style="text-align: center;vertical-align: middle;width:100px"><?php try{echo (Dedicacion::find()->where(['id_dedicacion' => $concurso['id_dedicacion']])->one()->descripcion_dedicacion);} catch(\Throwable $e){echo ('');} ?></th>
                        <td style="text-align: center;vertical-align: middle;width:70px"><?=$concurso['cantidad_de_puestos']?></th>
                        <td style="text-align: center;vertical-align: middle;width:150px">Desde <br><?=$concurso['fecha_inicio_inscripcion']?> <br>Hasta <br><?=$concurso['fecha_fin_inscripcion']?></th>
                        <td style="text-align: center;vertical-align: middle;width:100px"><?=$concurso['numero_expediente']?></td>
                        <td style="width:70px;"> 
                            <div class="col" style="display:grid;justify-content: center;">
                                <div class="row ml-2 my-2">
                                    <div class="btn btn-block vermas"
                                    value="<?=$concurso['id_concurso']?>"
                                    style="text-align: center;color:white;width:80px;background-color:#40BB97;font-weight:600;font-size:10px">
                                        Ver más
                                    </div>
                                </div>
                                <div class="row ml-2 my-2">
                                    <div class="btn btn-block" style="text-align: center;color:white;width:80px;background-color:#40BB97;font-weight:600;font-size:10px">
                                        Formulario
                                    </div>
                                </div>
                            </div>               
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9">
                            <hr style="border-bottom: 1px dotted gray;">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif ?>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-body" id="modalform" style="background-color: #F5F5F5;">
            </div>
            </div>
        </div>
    </div>

</div>

<?php
    $this->registerJs("
    var selectua;
    $('#facultades').on('change', function() {
        $('#areas').empty();
        selectua = $(this).val()
        if (selectua <= 0)
        {
            $('#filtro').attr('href','concurso');
            $('#areas').empty();
            $('#areas').prop('disabled',true);
        }
        else
        {
            $('#filtro').attr('href','concurso?ua='+selectua);
            $.ajax({
                url: '".Url::to(['concurso/area'])."',            
                method: 'POST',
                data: {
                    facultad: selectua,
                },
                success: function(data) {
                    $('#areas').prop('disabled',false);
                    var obj = $.parseJSON(data);
                    $('#areas').append('<option value=\"0\"></option>');
                    $.each(obj, function(index, value) {
                        $('#areas').append('<option value=\"' + value.id_area_departamento + '\">' + value.descripcion_area_departamento + '</option>');
                    });
                }
            });
        }
    });
    ",$this::POS_READY);
?>

<?php
    $this->registerJs("
    $('#areas').on('change', function() {
        if ($(this).val() <= 0)
        {
            $('#filtro').attr('href','concurso?ua='+selectua);
        }
        else
        {
            $('#filtro').attr('href','concurso?ua='+selectua+'&ar='+$(this).val());
        }
    });
    ",$this::POS_READY);
?>

<?php
    $this->registerJs("
    $('.vermas').on('click', function (event) {
        let id=$(this).attr('value')
        event.preventDefault();
        $('#modalform').load('concurso/formulario?id='+id);
        $('#exampleModal').modal('show');
      })
    ",$this::POS_READY);
?>