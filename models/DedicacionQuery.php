<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Dedicacion]].
 *
 * @see Dedicacion
 */
class DedicacionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Dedicacion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Dedicacion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
