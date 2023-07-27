<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Preinscripto]].
 *
 * @see Preinscripto
 */
class PreinscriptoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Preinscripto[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Preinscripto|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
