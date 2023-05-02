<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\User\Model;

use Da\User\Helper\GravatarHelper;
use Da\User\Query\ProfileQuery;
use Da\User\Traits\ContainerAwareTrait;
use Da\User\Traits\ModuleAwareTrait;
use Da\User\Validator\TimeZoneValidator;
use DateTime;
use DateTimeZone;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;

/**
 * @property int    $user_id
 * @property string $name
 * @property string $public_email
 * @property string $gravatar_email
 * @property string $gravatar_id
 * @property string $location
 * @property string $website
 * @property string $bio
 * @property string $timezone
 * @property User   $user
 */
class Profile extends ActiveRecord
{
    use ModuleAwareTrait;
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @throws InvalidParamException
     * @throws InvalidConfigException
     */
    public function beforeSave($insert)
    {
        if ($this->isAttributeChanged('gravatar_email')) {
            $this->setAttribute(
                'gravatar_id',
                $this->make(GravatarHelper::class)->buildId(trim($this->getAttribute('gravatar_email')))
            );
        }

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    public function rules()
    {
        return [
            [['id_tipo_documento'], 'integer'],
            [['sexo'], 'required'],
            [['numero_documento'], 'string', 'max' => 12],
            [['apellido', 'nombre', 'email'], 'string', 'max' => 100],
            [['numero_legajo', 'id_trato', 'proveedor_celular'], 'string', 'max' => 10],
            [['sexo'], 'string', 'max' => 1],
            [['numero_celular_sms'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_documento' => Yii::t('app', 'Id Tipo Documento'),
            'numero_documento' => Yii::t('app', 'Numero Documento'),
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

    /**
     * @return ProfileQuery
     */
    public static function find()
    {
        return new ProfileQuery(static::class);
    }
}
