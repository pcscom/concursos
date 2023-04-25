<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dedicacion".
 *
 * @property int $id_dedicacion
 * @property string $descripcion_dedicacion
 */
class Dedicacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dedicacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion_dedicacion'], 'required'],
            [['descripcion_dedicacion'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_dedicacion' => Yii::t('app', 'Id Dedicacion'),
            'descripcion_dedicacion' => Yii::t('app', 'Descripcion Dedicacion'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DedicacionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DedicacionQuery(get_called_class());
    }
}
