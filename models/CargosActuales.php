<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cargos_actuales".
 *
 * @property int $id
 * @property string|null $designacion
 * @property string|null $categoria
 * @property string|null $dedicacion
 * @property string|null $asignatura
 * @property string|null $facultad
 * @property string|null $universidad
 * @property int|null $user_id
 */
class CargosActuales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cargos_actuales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'user_id'], 'integer'],
            [['designacion', 'categoria', 'dedicacion', 'asignatura', 'facultad', 'universidad'], 'string', 'max' => 45],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'designacion' => Yii::t('app', 'Designacion'),
            'categoria' => Yii::t('app', 'Categoria'),
            'dedicacion' => Yii::t('app', 'Dedicacion'),
            'asignatura' => Yii::t('app', 'Asignatura'),
            'facultad' => Yii::t('app', 'Facultad'),
            'universidad' => Yii::t('app', 'Universidad'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CargosActualesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CargosActualesQuery(get_called_class());
    }
}
