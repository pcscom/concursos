<?php

namespace app\models;
use yii\base\Model;

/**
 * This is the ActiveQuery class for [[Adjuntos]].
 *
 * @see Adjuntos
 */
class AdjuntosQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Adjuntos[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Adjuntos|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
