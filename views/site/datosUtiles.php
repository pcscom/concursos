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
    <div class="mt-4" style="display: flex;flex-direction: row;justify-content: space-between;">
        <div style="width:fit-content;display:flex;flex-direction:column">
            <div id="btn-1" value="1" class="ua button color-btn active" onclick="changeColor(this)">Agronomia</div>
            <div id="btn-2" value="2" class="ua button color-btn" onclick="changeColor(this)">Arquitectura, Diseño y Urbanismo</div>
            <div id="btn-3" value="75" class="ua button color-btn" onclick="changeColor(this)">Ciclo Básico Común</div>
            <div id="btn-4" value="3" class="ua button color-btn" onclick="changeColor(this)">Ciencias Económicas</div>
            <div id="btn-5" value="4" class="ua button color-btn" onclick="changeColor(this)">Ciencias Exactas y Naturales</div>
            <div id="btn-6" value="5" class="ua button color-btn" onclick="changeColor(this)">Ciencias Sociales</div>
            <div id="btn-7" value="6" class="ua button color-btn" onclick="changeColor(this)">Ciencias Veterinarias</div>
            <div id="btn-8" value="7" class="ua button color-btn" onclick="changeColor(this)">Derecho</div>
            <div id="btn-9" value="8" class="ua button color-btn" onclick="changeColor(this)">Farmacia y Bioquímica</div>
            <div id="btn-10" value="9" class="ua button color-btn" onclick="changeColor(this)">Filosofía y Letras</div>
            <div id="btn-11" value="10" class="ua button color-btn" onclick="changeColor(this)">Ingeniería</div>
            <div id="btn-12" value="11" class="ua button color-btn" onclick="changeColor(this)">Medicina</div>
            <div id="btn-13" value="12" class="ua button color-btn" onclick="changeColor(this)">Odontología</div>
            <div id="btn-14" value="13" class="ua button color-btn" onclick="changeColor(this)">Psicología</div>
        </div>
        <div class="col" id="duform" style="display: flex;flex-direction: column;align-items: center;justify-content: center;max-width: 50%;">
        </div>
    <div>
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
    
    }
</script>

<?php
    $this->registerJs("
    $('.ua').on('click', function (event) {
        let id=$(this).attr('value')
        $('#duform').load('formulario?id='+id);
      })
    ",$this::POS_READY);
?>

<?php
    $this->registerJs("
    $(document).ready(function() {  
        $('#duform').load('formulario');
    });
",$this::POS_READY);
?>