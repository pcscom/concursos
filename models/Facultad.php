<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "facultad".
 *
 * @property int $id_facultad
 * @property string $nombre_facultad
 * @property string|null $uba
 * @property string|null $informacion_inscripcion
 * @property string|null $email
 * @property string|null $telefono
 * @property string|null $horario
 */
class Facultad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'facultad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_facultad', 'nombre_facultad'], 'required'],
            [['id_facultad'], 'integer'],
            [['nombre_facultad', 'email'], 'string', 'max' => 70],
            [['uba'], 'string', 'max' => 1],
            [['informacion_inscripcion'], 'string', 'max' => 255],
            [['telefono'], 'string', 'max' => 50],
            [['horario'], 'string', 'max' => 100],
            [['id_facultad'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_facultad' => Yii::t('app', 'Id Facultad'),
            'nombre_facultad' => Yii::t('app', 'Nombre Facultad'),
            'uba' => Yii::t('app', 'Uba'),
            'informacion_inscripcion' => Yii::t('app', 'Informacion Inscripcion'),
            'email' => Yii::t('app', 'Email'),
            'telefono' => Yii::t('app', 'Telefono'),
            'horario' => Yii::t('app', 'Horario'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FacultadQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FacultadQuery(get_called_class());
    }
}
