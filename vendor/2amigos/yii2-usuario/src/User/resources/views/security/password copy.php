<?php
Use yii\helpers\Html;
Use yii\helpers\CHtml;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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

</style>

<div id="main-content" class="justify-content-center text-center">
    <div class="panel-body">
        <div class="p-3 shadow rounded container"  style="width:85%"> 
        <h4 class="text-center mb-4"><strong>Cambiar contraseña</strong></h4>
            <div class="filters-form">     
                <?php
                    $form = ActiveForm::begin(
                        [
                            'id' => 'Passwordchange',
                            'options' => ['class' => 'form-horizontal','autocomplete' => 'off'],
                            'fieldConfig' => [
                                'labelOptions' => ['class' => 'col-lg-3',],
                            ],
                            'enableAjaxValidation' => false,
                            'enableClientValidation' => true,
                            'validateOnBlur' => false,
                        ]
                    );
                ?>
                            
                <div class="col-lg-12">
                    <div class="row span6 justify-content-center">
                    <h6 class=" ml-2 mr-1" style="height:20px;">Contraseña actual</h6>
                        <?= $form->field($pass, 'oldpass')->label(false)->textInput()->passwordInput(['id' => 'oldpass','readonly' => false, 'style'=>"width:200px;font-size:15px;border:none;background:none;height:20px;border-bottom:solid 1px;"])?>
                        <label class="container" style="text-align:right;margin-right:0px;margin-left:5px;margin-top:1px;width:15px;height:15px;">
                            <input id="reveal-oldpass" type="checkbox" checked="unchecked">
                            <span class="checkmark" ></span>
                        </label>
                    </div>

                    <div class="row justify-content-center">
                        <h6 class=" ml-2 mr-1" style="height:20px;">Contraseña nueva</h6>
                        <?= $form->field($pass, 'newpass')->label(false)->textInput()->passwordInput(['id' => 'newpass','readonly' => false, 'style'=>"width:200px;font-size:15px;border:none;background:none;height:20px;border-bottom:solid 1px;"])?>
                        <label class="container" style="text-align:right;margin-right:0px;margin-left:5px;margin-top:1px;width:15px;height:15px;">
                            <input id="reveal-newpass" type="checkbox" checked="unchecked">
                            <span class="checkmark" ></span>
                        </label>
                    </div>
                    <div class="row justify-content-center">
                        <h6 class=" ml-2 mr-1" style="height:20px;">Repetir contraseña</h6>
                        <?= $form->field($pass, 'newpassagain')->label(false)->textInput()->passwordInput(['id' => 'newpassagain','readonly' => false, 'style'=>"width:192px;font-size:15px;border:none;background:none;height:20px;border-bottom:solid 1px;"])?>
                        <label class="container" style="text-align:right;margin-right:0px;margin-left:5px;margin-top:1px;width:15px;height:15px;">
                            <input id="reveal-newpassagain" type="checkbox" checked="unchecked">
                            <span class="checkmark" ></span>
                        </label>
                    </div>           
            </div>
        </div>
        <div class="row mt-2 pt-2 pb-2" style="align-items: center;">
            <?= Html::submitButton(Yii::t('usuario', 'Cambiar'), ['class' => 'btn fancybutton','value' => 'changepass','method' => 'post']) ?>
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

?>

