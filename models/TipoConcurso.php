<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_concurso".
 *
 * @property int $id_tipo_concurso
 * @property string $descripcion_tipo_concurso
 */
class TipoConcurso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_concurso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion_tipo_concurso'], 'required'],
            [['descripcion_tipo_concurso'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_concurso' => Yii::t('app', 'Id Tipo Concurso'),
            'descripcion_tipo_concurso' => Yii::t('app', 'Descripcion Tipo Concurso'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return TipoConcursoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TipoConcursoQuery(get_called_class());
    }
}
