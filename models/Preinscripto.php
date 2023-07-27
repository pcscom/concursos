<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preinscripto".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $concurso_id
 * @property string|null $doc
 */
class Preinscripto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'preinscripto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'concurso_id'], 'integer'],
            [['doc'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'concurso_id' => Yii::t('app', 'Concurso ID'),
            'doc' => Yii::t('app', 'Doc'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return PreinscriptoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PreinscriptoQuery(get_called_class());
    }
}
