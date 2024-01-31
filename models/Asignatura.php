<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asignatura".
 *
 * @property int $id_asignatura
 * @property int $id_facultad
 * @property string $descripcion_asignatura
 * @property string $habilitada
 * @property string|null $numero_resolucion
 * @property string|null $observaciones
 */
class Asignatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asignatura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_asignatura', 'id_facultad', 'descripcion_asignatura', 'habilitada'], 'required'],
            [['id_asignatura', 'id_facultad'], 'integer'],
            [['descripcion_asignatura'], 'string', 'max' => 150],
            [['habilitada'], 'string', 'max' => 1],
            [['numero_resolucion'], 'string', 'max' => 10],
            [['observaciones'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_asignatura' => Yii::t('app', 'Id Asignatura'),
            'id_facultad' => Yii::t('app', 'Id Facultad'),
            'descripcion_asignatura' => Yii::t('app', 'Descripcion Asignatura'),
            'habilitada' => Yii::t('app', 'Habilitada'),
            'numero_resolucion' => Yii::t('app', 'Numero Resolucion'),
            'observaciones' => Yii::t('app', 'Observaciones'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AsignaturaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AsignaturaQuery(get_called_class());
    }
}
