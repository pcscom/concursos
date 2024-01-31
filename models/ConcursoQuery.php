<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Concurso;

/**
 * ConcursoQuery represents the model behind the search form of `app\models\Concurso`.
 */
class ConcursoQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_concurso'], 'required'],
            [['id_concurso', 'id_tipo_concurso', 'id_facultad', 'id_categoria', 'id_categoria_minima', 'id_dedicacion', 'id_dedicacion_minima', 'id_area_departamento', 'cantidad_de_puestos', 'id_tipo_informe', 'cantidad_dias_publicacion', 'id_tipo_presupuesto', 'ultimo_numero_movimiento', 'lck_concurso', 'fecha_sorteo_publicada', 'fecha_entrevista_prueba_publicada', 'estado_propuesta_jurados_preliminar', 'estado_propuesta_preliminar', 'firmantes_comision_seleccionados'], 'integer'],
            [['fecha_inicio_inscripcion', 'fecha_fin_inscripcion', 'fecha_publicacion', 'fecha_publicacion_prueba_oposicion'], 'safe'],
            [['numero_expediente'], 'string', 'max' => 10],
            [['hora_inicio_inscripcion', 'hora_fin_inscripcion'], 'string', 'max' => 5],
            [['comentario'], 'string', 'max' => 400],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Concurso::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query->andFilterWhere(['like', 'id_area_departamento', $this->id_area_departamento])
            ->andFilterWhere(['like', 'comentario', $this->comentario])
            ->andFilterWhere(['like', 'id_categoria', $this->id_categoria])
            ->andFilterWhere(['like', 'id_dedicacion', $this->id_dedicacion])
            ->andFilterWhere(['like', 'cantidad_de_puestos', $this->cantidad_de_puestos])
            ->andFilterWhere(['like', 'fecha_inicio_inscripcion', $this->fecha_inicio_inscripcion])
            ->andFilterWhere(['like', 'fecha_fin_inscripcion', $this->fecha_fin_inscripcion])
            ->andFilterWhere(['like', 'numero_expediente', $this->numero_expediente]);


        return $dataProvider;
    }

    // public function __construct()
    // {
    //     new ConcursoQuery(['rootTag' => 'newRootTag']);
    // }
}
