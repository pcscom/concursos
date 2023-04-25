<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Facultad]].
 *
 * @see Facultad
 */
class FacultadQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Facultad[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Facultad|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
