<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "area_departamento".
 *
 * @property int $id_area_departamento
 * @property int $id_facultad
 * @property string $descripcion_area_departamento
 * @property int $activa
 */
class AreaDepartamento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area_departamento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_area_departamento', 'id_facultad', 'descripcion_area_departamento', 'activa'], 'required'],
            [['id_area_departamento', 'id_facultad', 'activa'], 'integer'],
            [['descripcion_area_departamento'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_area_departamento' => Yii::t('app', 'Id Area Departamento'),
            'id_facultad' => Yii::t('app', 'Id Facultad'),
            'descripcion_area_departamento' => Yii::t('app', 'Descripcion Area Departamento'),
            'activa' => Yii::t('app', 'Activa'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AreaDepartamentoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AreaDepartamentoQuery(get_called_class());
    }
}
