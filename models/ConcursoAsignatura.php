<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "concurso_asignatura".
 *
 * @property int $id_concurso
 * @property int $id_asignatura
 * @property int $id_facultad
 */
class ConcursoAsignatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'concurso_asignatura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_concurso', 'id_asignatura', 'id_facultad'], 'required'],
            [['id_concurso', 'id_asignatura', 'id_facultad'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_concurso' => Yii::t('app', 'Id Concurso'),
            'id_asignatura' => Yii::t('app', 'Id Asignatura'),
            'id_facultad' => Yii::t('app', 'Id Facultad'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ConcursoAsignaturaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConcursoAsignaturaQuery(get_called_class());
    }
}
