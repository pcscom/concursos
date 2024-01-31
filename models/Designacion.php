<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "designacion".
 *
 * @property int $id_designacion
 * @property string $descripcion_designacion
 */
class Designacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'designacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_designacion', 'descripcion_designacion'], 'required'],
            [['id_designacion'], 'integer'],
            [['descripcion_designacion'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_designacion' => Yii::t('app', 'Id Designacion'),
            'descripcion_designacion' => Yii::t('app', 'Descripcion Designacion'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DesignacionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DesignacionQuery(get_called_class());
    }
}
