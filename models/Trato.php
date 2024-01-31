<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trato".
 *
 * @property int $id_trato
 * @property string $descripcion_trato
 * @property string $abreviatura_trato
 */
class Trato extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trato';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_trato', 'descripcion_trato', 'abreviatura_trato'], 'required'],
            [['id_trato'], 'integer'],
            [['descripcion_trato'], 'string', 'max' => 50],
            [['abreviatura_trato'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_trato' => Yii::t('app', 'Id Trato'),
            'descripcion_trato' => Yii::t('app', 'Descripcion Trato'),
            'abreviatura_trato' => Yii::t('app', 'Abreviatura Trato'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return TratoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TratoQuery(get_called_class());
    }
}
