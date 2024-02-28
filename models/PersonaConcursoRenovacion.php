<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "persona_concurso_renovacion".
 *
 * @property int $id_concurso
 * @property int|null $id_tipo_documento
 * @property string|null $numero_documento
 * @property int|null $id_categoria
 * @property int|null $id_dedicacion
 * @property int|null $id_designacion
 * @property int|null $subcargo
 * @property int|null $digito
 */
class PersonaConcursoRenovacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'persona_concurso_renovacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_concurso'], 'required'],
            [['id_concurso', 'id_tipo_documento', 'id_categoria', 'id_dedicacion', 'id_designacion', 'subcargo', 'digito'], 'integer'],
            [['numero_documento'], 'string', 'max' => 12],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_concurso' => Yii::t('app', 'Id Concurso'),
            'id_tipo_documento' => Yii::t('app', 'Id Tipo Documento'),
            'numero_documento' => Yii::t('app', 'Numero Documento'),
            'id_categoria' => Yii::t('app', 'Id Categoria'),
            'id_dedicacion' => Yii::t('app', 'Id Dedicacion'),
            'id_designacion' => Yii::t('app', 'Id Designacion'),
            'subcargo' => Yii::t('app', 'Subcargo'),
            'digito' => Yii::t('app', 'Digito'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return PersonaConcursoRenovacionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PersonaConcursoRenovacionQuery(get_called_class());
    }
}
