<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PersonaConcursoRenovacion]].
 *
 * @see PersonaConcursoRenovacion
 */
class PersonaConcursoRenovacionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PersonaConcursoRenovacion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PersonaConcursoRenovacion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
