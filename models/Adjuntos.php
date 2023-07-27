<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "adjuntos".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $url
 * @property int|null $tamano
 * @property string|null $tipo
 * @property string|null $fecha_creacion
 * @property int|null $user_id
 */
class Adjuntos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'adjuntos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tamano', 'user_id'], 'integer'],
            [['fecha_creacion'], 'safe'],
            [['nombre', 'url'], 'string', 'max' => 255],
            [['tipo'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nombre' => Yii::t('app', 'Nombre'),
            'url' => Yii::t('app', 'Url'),
            'tamano' => Yii::t('app', 'Tamano'),
            'tipo' => Yii::t('app', 'Tipo'),
            'fecha_creacion' => Yii::t('app', 'Fecha Creacion'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AdjuntosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdjuntosQuery(get_called_class());
    }
}
