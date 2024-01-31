<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AlertasClima;
use app\models\DateTime;
/**
 * AlertasClimaSearch represents the model behind the search form of `app\models\AlertasClima`.
 */
class AlertasClimaSearch extends AlertasClima
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['texto', 'desde', 'hasta'], 'safe'],
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
        $query = AlertasClima::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'desde' => $this->desde,
            // 'hasta' => $this->hasta,
        ]);
        $query->andFilterWhere(['like', 'texto', $this->texto]);
        // ->andFilterWhere(['<=', 'hasta', $this->hasta])
        // ->andFilterWhere(['>=', 'desde', $this->desde]);
        if (!is_null($this->desde) && (strpos($this->desde, ' - ') != false)) 
        {
        list($start_date, $end_date) = explode(' - ', $this->desde);
        $query->andFilterWhere(['between', 'date(clima_alertas.desde)', $start_date, $end_date]);
        }
        if (!is_null($this->hasta) && (strpos($this->hasta, ' - ') != false)) 
        {
        list($start_date, $end_date) = explode(' - ', $this->hasta);
        $query->andFilterWhere(['between', 'date(clima_alertas.hasta)', $start_date, $end_date]);
        }
        return $dataProvider;
    }
}
