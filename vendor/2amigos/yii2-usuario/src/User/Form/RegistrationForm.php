<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\User\Form;

use Da\User\Model\User;
use Da\User\Traits\ContainerAwareTrait;
use Da\User\Traits\ModuleAwareTrait;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class RegistrationForm extends Model
{
    use ModuleAwareTrait;
    use ContainerAwareTrait;

    /**
     * @var string User email address
     */
    public $email;
    /**
     * @var string Username
     */
    public $username;
    /**
     * @var string Password
     */
    public $password;
    public $confirmpassword;
    /**
     * @var bool Data processing consent
     */
    public $gdpr_consent;

    public $nombre;
    public $apellido;
    public $id_trato;
    public $numero_documento;
    public $numero_legajo;
    public $proveedor_celular;
    public $sexo;
    public $numero_celular_sms;
    

    public $cuil;
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function rules()
    {
        /** @var User $user */
        $user = $this->getClassMap()->get(User::class);

        return [
            // username rules
            'usernameLength' => ['username', 'string', 'min' => 3, 'max' => 12],
            'usernamePattern' => ['username', 'match', 'pattern' => '/^[-a-zA-Z0-9_\.@]+$/'],
            'usernameRequired' => ['username', 'required', 'message' => 'Este campo es obligatorio'],
            'usernameUnique' => [
                'username',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('usuario', 'Este documento ya esta siendo usado'),
            ],
            // email rules
            'emailRequired' => ['email', 'required', 'message' => 'Este campo es obligatorio'],
            'emailPattern' => ['email', 'email'],
            'emailUnique' => [
                'email',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('usuario', 'Este email ya esta siendo usado'),
            ],
            // password rules
            'passwordRequired' => [['password','confirmpassword'], 'required', 'message' => 'Este campo es obligatorio'],
            // 'passwordLength' => [['password','confirmpassword'], 'string', 'min' => 6, 'max' => 72],
            'gdprType' => ['gdpr_consent', 'boolean'],
            'gdprDefault' => ['gdpr_consent', 'default', 'value' => 0, 'skipOnEmpty' => false],
            'gdprRequired' => ['gdpr_consent',
                'compare',
                'compareValue' => true,
                'message' => Yii::t('usuario', 'Your consent is required to register'),
                'when' => function () {
                    return $this->module->enableGdprCompliance;
            }],

            //profile data
            'profileRequired' => [['apellido', 'nombre', 'email'], 'required', 'message' => 'Este campo es obligatorio'],
            [['apellido', 'nombre', 'email'], 'string', 'max' => 100],
            [['numero_legajo', 'id_trato', 'proveedor_celular'], 'string', 'max' => 10],
            [['sexo'], 'string', 'max' => 1],
            [['numero_celular_sms'], 'string', 'max' => 15],
            ['confirmpassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Las contraseñas no coinciden'],
            // cuil rules
            'cuilRequired' => ['cuil', 'required', 'message' => 'Este campo es obligatorio'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('usuario', 'Email'),
            'username' => Yii::t('usuario', 'Username'),
            'password' => Yii::t('usuario', 'Password'),
            'confirmpassword' => Yii::t('usuario', 'Confirmar Contraseña'),
            'gdpr_consent' => Yii::t('usuario', 'Data processing consent'),
            'user_id' => Yii::t('app', 'User ID'),
            'apellido' => Yii::t('app', 'Apellido'),
            'nombre' => Yii::t('app', 'Nombre'),
            'numero_legajo' => Yii::t('app', 'Numero Legajo'),
            'id_trato' => Yii::t('app', 'Id Trato'),
            'sexo' => Yii::t('app', 'Sexo'),
            'email' => Yii::t('app', 'Email'),
            'numero_celular_sms' => Yii::t('app', 'Numero Celular Sms'),
            'proveedor_celular' => Yii::t('app', 'Proveedor Celular'),
        ];
    }

    public function attributeHints()
    {
        return [
            'gdpr_consent' => $this->module->getConsentMessage()
        ];
    }

    public static function getDb()
    {
        return Yii::$app->get('db');
    }
}
