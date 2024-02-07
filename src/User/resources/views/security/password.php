<?php
Use yii\helpers\Html;
Use yii\helpers\CHtml;

use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use dosamigos\switchinput\SwitchBox;
use user\model\Profile;
use user\model\Passwordchange;
?>

<style>
    @import url("https://use.fontawesome.com/releases/v5.13.0/css/all.css");
     
    .checkmark:before {
    font-family: 'Font Awesome 5 Free';
    content: '\f06e';
    }
    :checked+.checkmark:before {
        font-family: 'Font Awesome 5 Free';
        content: '\f070';
    }
    input[type=checkbox] {
    display: none;
    }

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
                    <H1>Nueva contraseña</H1>
                    <!-- <H3 style="font-weight: 400;">Registrarse</H3> -->
                    <p class="text-center" style="position:absolute;bottom:5px">
                    <p style="text-decoration: none;color:black;font-weight: 200;font-family: Helvetica;">Luego de cambiar la contraseña, deberá volver a ingresar al portal.</p>
                </div>
            </div>
            <div class="col mx-0 px-0">
                <div class="card" style="display:flex;justify-content:center;border-radius:0;height:600px;width:400px;">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center">
                        <?php
                            $form = ActiveForm::begin(
                                [
                                    'id' => 'Passwordchange',
                                    'options' => ['autocomplete' => 'off'],
                                    'enableAjaxValidation' => false,
                                    'enableClientValidation' => false,
                                    'validateOnBlur' => false,
                                ]
                            );
                        ?>
                             
                        <div style="display:flex;flex-direction:row;">
                            <?= $form->field($pass, 'oldpass')->label(false)->textInput()->passwordInput([
                                'id' => 'oldpass',
                                'readonly' => false, 
                                // 'style'=>"width:200px;font-size:15px;border:none;background:none;height:20px;border-bottom:solid 1px;", 
                                'style' => 'border-radius:10px;background-color:transparent;width:250px;border:1px solid black', 
                                "placeholder" => "Contraseña actual"
                                ])?>
                            <label class="container" style="text-align:right;margin-right:0px;margin-left:5px;margin-top:1px;width:15px;height:15px;">
                                <input id="reveal-oldpass" type="checkbox" checked="unchecked">
                                <span class="checkmark" style="font-size:15px"></span>
                            </label>
                        </div>

                        <div style="display:flex;flex-direction:row;">
                            <?= $form->field($pass, 'newpass')->label(false)->textInput()->passwordInput([
                                'id' => 'newpass',
                                'readonly' => false, 
                                'style' => 'border-radius:10px;background-color:transparent;width:250px;border:1px solid black', 
                                "placeholder" => "Contraseña nueva"
                            ])?>
                            <label class="container" style="text-align:right;margin-right:0px;margin-left:5px;margin-top:1px;width:15px;height:15px;">
                                <input id="reveal-newpass" type="checkbox" checked="unchecked">
                                <span class="checkmark" style="font-size:15px"></span>
                            </label>
                        </div>
                        <div style="display:flex;flex-direction:row;">
                            <?= $form->field($pass, 'newpassagain')->label(false)->textInput()->passwordInput([
                                'id' => 'newpassagain','readonly' => false, 
                                'style' => 'border-radius:10px;background-color:transparent;width:250px;border:1px solid black', 
                                "placeholder" => "Repetir contraseña"
                            ])?>
                            <label class="container" style="text-align:right;margin-right:0px;margin-left:5px;margin-top:1px;width:15px;height:15px;">
                                <input id="reveal-newpassagain" type="checkbox" checked="unchecked">
                                <span class="checkmark" style="font-size:15px"></span>
                            </label>
                        </div>   
                        <div style="display:flex;flex-direction:row;justify-content:center">
                            <?= Html::submitButton(Yii::t('usuario', 'Cambiar'), [
                                'class' => 'btn',
                                'value' => 'changepass',
                                'style' => 'width:150px;background-color:#40BB97;font-weight:600',
                                'method' => 'post'
                                ]) ?>
                        </div>   
                    </div>
                    <div style="position: absolute;bottom: 5%;left: 10%;">
                        <div style="display:flex;flex-direction:row;width: 80%;">
                                <p style="text-decoration: none;color:black;font-weight: 200;font-family: Helvetica;">Su nueva contraseña debe tener al menos 8 caracteres. Se recomienda, por seguridad, tener en cuenta las siguientes pautas:</p>
                        </div>  
                        <div style="display:flex;flex-direction:row;width: 80%;">
                                <p style="text-decoration: none;color:black;font-weight: 200;font-family: Helvetica;">- Utilizar mayúsculas y minúsculas</p>
                        </div>  
                        <div style="display:flex;flex-direction:row;width: 80%;">
                                <p style="text-decoration: none;color:black;font-weight: 200;font-family: Helvetica;">- Utilizar letras y números</p>
                        </div>  
                        <div style="display:flex;flex-direction:row;width: 80%;">
                                <p style="text-decoration: none;color:black;font-weight: 200;font-family: Helvetica;">- Utilizar caracteres especiales</p>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>   
    </div>
</div>

<?php
$this->registerJs("
$(document).ready(function(){ 
    $('#reveal-oldpass').on('change',function(){  
        $('#oldpass').attr('type',this.checked?'password':'text');
   }) 
});
", $this::POS_READY);

$this->registerJs("
$(document).ready(function(){ 
    $('#reveal-newpass').on('change',function(){  
        $('#newpass').attr('type',this.checked?'password':'text');
   }) 
});
", $this::POS_READY);

$this->registerJs("
$(document).ready(function(){ 
    $('#reveal-newpassagain').on('change',function(){  
        $('#newpassagain').attr('type',this.checked?'password':'text');
   }) 
});
", $this::POS_READY);

$this->registerJs("
$(document).ready(function(){ 
    $('#w1').css('display', 'none');

});
", $this::POS_READY);
?>

