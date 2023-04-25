<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ConcursoAsignatura]].
 *
 * @see ConcursoAsignatura
 */
class ConcursoAsignaturaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConcursoAsignatura[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConcursoAsignatura|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
