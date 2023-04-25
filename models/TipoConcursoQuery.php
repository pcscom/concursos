<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TipoConcurso]].
 *
 * @see TipoConcurso
 */
class TipoConcursoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TipoConcurso[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TipoConcurso|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
