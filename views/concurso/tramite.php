<?php
/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

use kartik\select2\Select2;
use app\models\Profile;
use app\models\Concurso;
use app\models\ConcursoQuery;
use app\models\Facultad;
use app\models\AreaDepartamento;
use app\models\Categoria;
use app\models\ConcursoAsignatura;
use app\models\Asignatura;
use app\models\Dedicacion;
use app\models\Preinscripto;
use yii\helpers\FileHelper;

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
            <p style="margin:0;font-family:Helvetica"><b>Aviso:</b> Aquí podrán ver los concursos en los que realizaron preinscripciones. Advertencia para cumplir con el proceso de inscripción debe presentar el formulario de preinscripción en el sistema TAD.</p>
        </div>
        <div class="pt-2" style="display: flex;flex-direction: column;width: 934px;">
            <H1 style="font-weight:700;margin-bottom:0">Mis Concursos en Trámite<span id="pid-value"></span></H1>
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
                <p class="mx-4" style="font-size:18px;font-weight:400;height:15px">Área</p>
            </div>

            <div class="mt-2" style="display: flex;flex-direction:row-reverse;width: 100%;">
                <a id="filtro" href="#" style="text-decoration:none">
                    <div class="btn btn-block" style="width:120px;background-color:#40BB97;font-weight:600;font-size:12px">
                        FILTRAR
                    </div>
                </a>
            </div>
        </div>
        <?php $preinscripto=Preinscripto::find()->select('concurso_id')->where(['user_id' => Yii::$app->user->id])->column()?>
        <!-- BOTONES -->
        <?php $found=Concurso::find()->where(['IN', 'id_concurso', $preinscripto])->andWhere(['<=', 'fecha_inicio_inscripcion', date('Y-m-d')])->andWhere(['like','id_facultad',$ua,false])->andWhere(['OR', ['like', 'id_area_departamento', $ar, false], ['id_area_departamento' => null]])->count()?>
        <?php $botones=Concurso::find()->where(['like','id_facultad',$ua,false])->andWhere(['IN', 'id_concurso', $preinscripto])->groupBy('id_facultad')->all();
        ?>

        <?php if(!$found):?>
            <H4 id="emptymessage">No se encontraron llamados activos.</H4>
        <?php else:?>
            <div class="pt-4" style="display:flex;flex-wrap:wrap;width: 934px;flex-direction:row;">
                <?php 
                    $botones=Concurso::find()->where(['like','id_facultad',$ua,false])->andWhere(['IN', 'id_concurso', $preinscripto])->groupBy('id_facultad')->all();
                    foreach ($botones as $boton): 
                ?>
                        <?php 
                            try{
                                $activos=Concurso::find()->where(['like','id_facultad',$boton['id_facultad'],false])->andWhere(['OR', ['like', 'id_area_departamento', $ar, false], ['id_area_departamento' => null]])->andWhere(['IN', 'id_concurso', $preinscripto])->andWhere(['<=', 'fecha_inicio_inscripcion', date('Y-m-d')])->count();//date('Y-m-d')])->count();
                            } 
                            catch(\Throwable $e){
                                $activos=0;
                            }
                            if ( $activos > 0) : 
                        ?>
                                <?php $href="tramite?ua=".$boton['id_facultad']."&ar=".$ar ?>
                                <a href="<?=$href?>" style="cursor:pointer;text-decoration:none;width:45%">
                                    <div class="py-2" style="display:flex;flex-direction:row;">
                                        <div class="boton">
                                            <span><?= $activos ?></span>
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
                        <?php 
                            endif 
                        ?>
                <?php 
                    endforeach; 
                ?>
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
                        // $activos=Concurso::find()->where(['like','id_facultad',$boton['id_facultad'],false])->andWhere(['IN', 'id_concurso', $preinscripto])->andWhere(['OR', ['like', 'id_area_departamento', $boton['id_area_departamento'], false], ['id_area_departamento' => null]])->andWhere(['<=', 'fecha_inicio_inscripcion', date('Y-m-d')])->count();//date('Y-m-d')])->count();
                        $concursos=Concurso::find()->where(['like','id_facultad',$ua,false])->andWhere(['IN', 'id_concurso', $preinscripto])->andWhere(['OR', ['like', 'id_area_departamento', $ar, false], ['id_area_departamento' => null]])->andWhere(['<=', 'fecha_inicio_inscripcion', date('Y-m-d')])->orderBy(['fecha_inicio_inscripcion' => SORT_DESC])->all();
                        foreach ($concursos as $concurso): 
                    ?>
                        <tr>
                            <td style="text-align: center;vertical-align: middle;width:100px"><?php try{echo (AreaDepartamento::find()->where(['id_area_departamento' => $concurso['id_area_departamento']])->andWhere(['id_facultad' => $concurso['id_facultad']])->one()->descripcion_area_departamento);} catch(\Throwable $e){echo ('');} ?></th>
                            <?php 
                                try{
                                    $concursoAsignaturas=ConcursoAsignatura::find()->where(['id_concurso' => $concurso['id_concurso']])->all();
                                    $idAsignaturaArray = [];
                                    foreach ($concursoAsignaturas as $concursoAsignatura) {
                                        if ($concursoAsignatura instanceof ConcursoAsignatura) {
                                            $idAsignaturaArray[] = Asignatura::find()->where(['id_asignatura' => $concursoAsignatura->id_asignatura])->one()->descripcion_asignatura;
                                        }
                                    }
                                } 
                                catch(\Throwable $e){
                                    $asignatura='';
                                }
                            ?>
                            <td style="text-align: center;vertical-align: middle;width:150px"><?= implode('<br>', $idAsignaturaArray) ?></th>
                            <td style="text-align: center;vertical-align: middle;width:100px"><?php try{echo (Categoria::find()->where(['id_categoria' => $concurso['id_categoria']])->one()->descripcion_categoria);} catch(\Throwable $e){echo ('');}?></th>
                            <td style="text-align: center;vertical-align: middle;width:100px"><?php try{echo (Dedicacion::find()->where(['id_dedicacion' => $concurso['id_dedicacion']])->one()->descripcion_dedicacion);} catch(\Throwable $e){echo ('');} ?></th>
                            <td style="text-align: center;vertical-align: middle;width:70px"><?=$concurso['cantidad_de_puestos']?></th>
                            <td style="text-align: center;vertical-align: middle;width:150px">Desde <br><?=date("d/m/Y H:i", strtotime($concurso['fecha_inicio_inscripcion']))?> <br>Hasta <br><?=date("d/m/Y H:i", strtotime($concurso['fecha_fin_inscripcion']))?></th>
                            <td style="text-align: center;vertical-align: middle;width:100px"><?=$concurso['numero_expediente']?></td>
                            <td style="width:70px;"> 
                                <div class="col" style="display:grid;justify-content: center;">
                                    <div class="row ml-2 my-2" style="justify-content:center">
                                        <div class="btn btn-block vermas"
                                        value="<?=$concurso['id_concurso']?>"
                                        style="text-align: center;color:white;width:100px;background-color:#40BB97;font-weight:600;font-size:10px">
                                            Ver más
                                        </div>
                                    </div>
                                    <div class="row ml-2 my-2" style="justify-content:center">
                                        <div class="btn btn-block eliminar"
                                        value="<?=$concurso['id_concurso']?>"
                                        style="text-align: center;color:white;width:100px;background-color:#E35555;font-weight:600;font-size:10px;display:none">
                                            Eliminar
                                        </div>
                                    </div>
                                    <div class="row ml-2 my-2">
                                        <?php         
                                            $profile = Profile::findOne(['user_id' => Yii::$app->user->id]);
                                            $file = FileHelper::findFiles('attachments/formularios', [
                                                'only' => ['*' . $profile->cuil.'_' . $concurso['id_concurso'] . '*' . 'pdf'],
                                            ]);
                                        ?>
                                        <?php if($file && basename($file[0])): ?>
                                        <a style="text-decoration: none;" href='descargar?ruta=<?=basename($file[0])?>' download>
                                            <div class="btn btn-block" 
                                                style="text-align: center;color:white;width:100px;background-color:#40BB97;font-weight:600;font-size:10px"
                                            >
                                                Descargar formulario
                                            </div>
                                        </a>
                                        <?php endif ?>
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
        <?php endif?>
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

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Atención</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="font-weight:400">¿Estás seguro de que deseas eliminar la preinscripción?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn" style="background-color:#E35555;color:white" id="confirmButton">Eliminar</button>
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
            $('#filtro').attr('href','tramite?ua='+selectua);
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
            $('#filtro').attr('href','tramite?ua='+selectua);
        }
        else
        {
            $('#filtro').attr('href','tramite?ua='+selectua+'&ar='+$(this).val());
        }
    });
    ",$this::POS_READY);
?>

<?php
    $js = <<< JS
    let pid = 0;
    $('.vermas').on('click', function (event) {
        let id=$(this).attr('value')
        event.preventDefault();
        $('#modalform').load('formulario?id='+id);
        $('#exampleModal').modal('show');
    })

    $('.eliminar').on('click', function (event) {
        pid=$(this).attr('value')
        // $("#pid-value").text(pid)
        event.preventDefault();
        $('#confirmationModal').modal('show');
    })

    $('.preinscribirse').on('click', function(event) {
        var concursoid = $(this).attr('value');
        $.ajax({
            type: 'POST',
            url: 'concurso/preinscripcion',
            data: { id: concursoid },
        });
        location.reload();
    });
    
    $('#confirmButton').on('click', function (event) {
        $('#confirmationModal').modal('hide');

        $.ajax({
            url: 'desinscribir', 
            method: 'POST',
            data: { pid: pid },
            // success: function(data) {
            //     console.log(data);
            // },            
            // error: function(data) {
            //     console.log(data);
            // },

        });
        location.reload();
    });

    JS;
    $this->registerJs($js);
?>