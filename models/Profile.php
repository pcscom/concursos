<?php

namespace app\models;

use Yii;
use Da\User\Model\User;
/**
 * This is the model class for table "profile".
 *
 * @property int $user_id
 * @property string|null $numero_documento
 * @property string|null $apellido
 * @property string|null $nombre
 * @property string|null $numero_legajo
 * @property string|null $id_trato
 * @property string|null $sexo
 * @property string|null $email
 * @property string|null $numero_celular_sms
 * @property string|null $proveedor_celular
 * @property int|null $gdpr_consent
 * @property string|null $cuil
 * @property string|null $estado_civil
 * @property string|null $conyuge
 * @property string|null $madre
 * @property string|null $padre
 * @property string|null $nacimiento_localidad
 * @property string|null $nacimiento_fecha
 * @property string|null $nacimiento_expedido
 * @property string|null $nacimiento_pais
 * @property string|null $domicilio_calle
 * @property string|null $domicilio_numero
 * @property string|null $domicilio_piso
 * @property string|null $domicilio_departamento
 * @property string|null $domicilio_codigo_postal
 * @property string|null $domicilio_localidad
 * @property string|null $domicilio_provincia
 * @property string|null $domicilio_pais
 * @property string|null $titulos_obtenidos
 * @property string|null $antecedentes_docentes
 * @property string|null $antecedentes_cientificos
 * @property string|null $cursos
 * @property string|null $congresos
 * @property string|null $actuacion_universidades
 * @property string|null $formacion_rrhh
 * @property string|null $sintesis_aportes
 * @property string|null $sintesis_profesional
 * @property string|null $otros_antecedentes
 * @property string|null $labor_docente
 * @property string|null $renovacion
 * @property string|null $cid
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    public string $cid = '0';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    public function rules()
    {
        return [
            [['numero_documento','user_id','apellido', 'nombre', 'email', 'cuil','nacimiento_localidad', 'nacimiento_expedido', 'nacimiento_pais', 'domicilio_calle', 'domicilio_numero', 'domicilio_codigo_postal', 'domicilio_localidad', 'domicilio_provincia', 'domicilio_pais', 'nacimiento_fecha', 'estado_civil', 'numero_celular_sms'], 'required'],
            [['user_id', 'gdpr_consent'], 'integer'],
            [['titulos_obtenidos','antecedentes_docentes', 'antecedentes_cientificos', 'cursos', 'congresos', 'actuacion_universidades', 'formacion_rrhh', 'sintesis_aportes', 'sintesis_profesional', 'otros_antecedentes', 'labor_docente', 'renovacion','cargo_actual'], 'string'],
            [['numero_documento'], 'string', 'max' => 12],
            [['apellido', 'nombre', 'email'], 'string', 'max' => 100],
            [['numero_legajo', 'id_trato'], 'string', 'max' => 50],
            [['proveedor_celular'], 'string', 'max' => 10],
            [['domicilio_codigo_postal'], 'string', 'max' => 8],
            [['domicilio_codigo_postal'], 'string', 'min' => 8],
            [['sexo'], 'string', 'max' => 20],
            [['numero_celular_sms'], 'string', 'max' => 15],
            [['cuil', 'estado_civil', 'conyuge', 'madre', 'padre', 'nacimiento_localidad', 'nacimiento_expedido', 'nacimiento_pais', 'domicilio_calle', 'domicilio_numero', 'domicilio_piso', 'domicilio_departamento', 'domicilio_codigo_postal', 'domicilio_localidad', 'domicilio_provincia', 'domicilio_pais'], 'string', 'max' => 45],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['cid'], 'safe'],
            [['cid'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'numero_documento' => Yii::t('app', 'Numero Documento'),
            'apellido' => Yii::t('app', 'Apellido'),
            'nombre' => Yii::t('app', 'Nombre'),
            'numero_legajo' => Yii::t('app', 'Numero Legajo'),
            'id_trato' => Yii::t('app', 'Id Trato'),
            'sexo' => Yii::t('app', 'Sexo'),
            'email' => Yii::t('app', 'Email'),
            'numero_celular_sms' => Yii::t('app', 'Numero Celular Sms'),
            'proveedor_celular' => Yii::t('app', 'Proveedor Celular'),
            'gdpr_consent' => Yii::t('app', 'Gdpr Consent'),
            'cuil' => Yii::t('app', 'Cuil'),
            'estado_civil' => Yii::t('app', 'Estado Civil'),
            'conyuge' => Yii::t('app', 'Conyuge'),
            'madre' => Yii::t('app', 'Madre'),
            'padre' => Yii::t('app', 'Padre'),
            'nacimiento_localidad' => Yii::t('app', 'Localidad de Nacimiento'),
            'nacimiento_fecha' => Yii::t('app', 'Fecha de Nacimiento'),
            'nacimiento_expedido' => Yii::t('app', 'Provincia de Nacimiento'),
            'nacimiento_pais' => Yii::t('app', 'País de Nacimiento'),
            'domicilio_calle' => Yii::t('app', 'Calle de Domicilio'),
            'domicilio_numero' => Yii::t('app', 'Numero de Domicilio'),
            'domicilio_piso' => Yii::t('app', 'Piso de Departamenti'),
            'domicilio_departamento' => Yii::t('app', 'Número de Departamento'),
            'domicilio_codigo_postal' => Yii::t('app', 'Codigo Postal'),
            'domicilio_localidad' => Yii::t('app', 'Localidad de Domicilio'),
            'domicilio_provincia' => Yii::t('app', 'Provincia de Domicilio'),
            'domicilio_pais' => Yii::t('app', 'Pais de Domicilio'),
            'titulos_obtenidos' => Yii::t('app', 'Titulos Obtenidos'),
            'antecedentes_docentes' => Yii::t('app', 'Antecedentes Docentes'),
            'antecedentes_cientificos' => Yii::t('app', 'Antecedentes Cientificos'),
            'cursos' => Yii::t('app', 'Cursos'),
            'congresos' => Yii::t('app', 'Congresos'),
            'actuacion_universidades' => Yii::t('app', 'Actuacion Universidades'),
            'formacion_rrhh' => Yii::t('app', 'Formacion Rrhh'),
            'sintesis_aportes' => Yii::t('app', 'Sintesis Aportes'),
            'sintesis_profesional' => Yii::t('app', 'Sintesis Profesional'),
            'otros_antecedentes' => Yii::t('app', 'Otros Antecedentes'),
            'labor_docente' => Yii::t('app', 'Labor Docente'),
            'renovacion' => Yii::t('app', 'Renovacion'),
            'cargo_actual' => Yii::t('app', 'Cargo Actual'),
            'cid' => Yii::t('app', 'cid'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return ProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProfileQuery(get_called_class());
    }

    public static function primaryKey()
    {
        return ['user_id'];
    }
}
