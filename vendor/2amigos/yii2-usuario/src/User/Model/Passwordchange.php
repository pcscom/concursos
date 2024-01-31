<?php

namespace Da\User\Model;

use yii\base\Model;
use Yii;
class Passwordchange extends Model
{
    public $oldpass;
    public $newpass;
    public $newpassagain;

    public function rules()
    {
        return [

            // Application Name
            ['oldpass', 'required','message' => 'Contraseña actual no puede estar vacío'],

            // Application Backend Theme
            ['newpass', 'required','message' => 'Contraseña nueva no puede estar vacío'],
            'newpassMatch' => ['newpass', 'match', 'pattern' => '/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/','message' => 'Debe contener al menos 8 dígitos, números y mayúsculas'],
            'newpassLength' => ['newpass', 'string', 'min' => 8, 'max' => 255],
            'newpassTrim' => ['newpass', 'trim'],

            // Application Frontend Theme
            ['newpassagain', 'required','message' => 'Repetir contraseña no puede estar vacío'],
            'newpassagainMatch' => ['newpassagain', 'match', 'pattern' => '/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/','message' => 'Debe contener al menos 8 dígitos, un número, mayúsculas y minúsculas'],
            'newpassagainLength' => ['newpassagain', 'string', 'min' => 8, 'max' => 255],
            'newpassagainTrim' => ['newpassagain', 'trim'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'oldpass' => '',
            'newpass' => '',
            'newpassagain' => '',
            'appBackendTheme' => 'Repetir contraseña',
        ];
    }
}

