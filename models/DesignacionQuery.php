<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Designacion]].
 *
 * @see Designacion
 */
class DesignacionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Designacion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Designacion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
