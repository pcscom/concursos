<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categoria".
 *
 * @property int $id_categoria
 * @property string $descripcion_categoria
 * @property int|null $orden
 * @property string|null $solo_jurado
 * @property string|null $mostrar_en_propuesta
 * @property string|null $solo_aspirantes
 */
class Categoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_categoria', 'descripcion_categoria'], 'required'],
            [['id_categoria', 'orden'], 'integer'],
            [['descripcion_categoria'], 'string', 'max' => 30],
            [['solo_jurado', 'mostrar_en_propuesta', 'solo_aspirantes'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_categoria' => Yii::t('app', 'Id Categoria'),
            'descripcion_categoria' => Yii::t('app', 'Descripcion Categoria'),
            'orden' => Yii::t('app', 'Orden'),
            'solo_jurado' => Yii::t('app', 'Solo Jurado'),
            'mostrar_en_propuesta' => Yii::t('app', 'Mostrar En Propuesta'),
            'solo_aspirantes' => Yii::t('app', 'Solo Aspirantes'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CategoriaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoriaQuery(get_called_class());
    }
}
