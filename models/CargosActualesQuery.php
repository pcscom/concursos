<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CargosActuales]].
 *
 * @see CargosActuales
 */
class CargosActualesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CargosActuales[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CargosActuales|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
