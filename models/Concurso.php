<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "concurso".
 *
 * @property int $id_concurso
 * @property string|null $numero_expediente
 * @property int|null $id_tipo_concurso
 * @property int|null $id_facultad
 * @property int|null $id_categoria
 * @property int|null $id_categoria_minima
 * @property int|null $id_dedicacion
 * @property int|null $id_dedicacion_minima
 * @property int|null $id_area_departamento
 * @property int|null $cantidad_de_puestos
 * @property string|null $fecha_inicio_inscripcion
 * @property string|null $fecha_fin_inscripcion
 * @property string|null $hora_inicio_inscripcion
 * @property string|null $hora_fin_inscripcion
 * @property int|null $id_tipo_informe
 * @property string|null $fecha_publicacion
 * @property int|null $cantidad_dias_publicacion
 * @property string|null $fecha_publicacion_prueba_oposicion
 * @property int|null $id_tipo_presupuesto
 * @property int|null $ultimo_numero_movimiento
 * @property string|null $comentario
 * @property int|null $lck_concurso
 * @property int|null $fecha_sorteo_publicada
 * @property int|null $fecha_entrevista_prueba_publicada
 * @property int|null $estado_propuesta_jurados_preliminar
 * @property int|null $estado_propuesta_preliminar
 * @property int|null $firmantes_comision_seleccionados
 */
class Concurso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'concurso';
    }

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
    public function attributeLabels()
    {
        return [
            'id_concurso' => Yii::t('app', 'Id Concurso'),
            'numero_expediente' => Yii::t('app', 'Numero Expediente'),
            'id_tipo_concurso' => Yii::t('app', 'Id Tipo Concurso'),
            'id_facultad' => Yii::t('app', 'Id Facultad'),
            'id_categoria' => Yii::t('app', 'Id Categoria'),
            'id_categoria_minima' => Yii::t('app', 'Id Categoria Minima'),
            'id_dedicacion' => Yii::t('app', 'Id Dedicacion'),
            'id_dedicacion_minima' => Yii::t('app', 'Id Dedicacion Minima'),
            'id_area_departamento' => Yii::t('app', 'Id Area Departamento'),
            'cantidad_de_puestos' => Yii::t('app', 'Cantidad De Puestos'),
            'fecha_inicio_inscripcion' => Yii::t('app', 'Fecha Inicio Inscripcion'),
            'fecha_fin_inscripcion' => Yii::t('app', 'Fecha Fin Inscripcion'),
            'hora_inicio_inscripcion' => Yii::t('app', 'Hora Inicio Inscripcion'),
            'hora_fin_inscripcion' => Yii::t('app', 'Hora Fin Inscripcion'),
            'id_tipo_informe' => Yii::t('app', 'Id Tipo Informe'),
            'fecha_publicacion' => Yii::t('app', 'Fecha Publicacion'),
            'cantidad_dias_publicacion' => Yii::t('app', 'Cantidad Dias Publicacion'),
            'fecha_publicacion_prueba_oposicion' => Yii::t('app', 'Fecha Publicacion Prueba Oposicion'),
            'id_tipo_presupuesto' => Yii::t('app', 'Id Tipo Presupuesto'),
            'ultimo_numero_movimiento' => Yii::t('app', 'Ultimo Numero Movimiento'),
            'comentario' => Yii::t('app', 'Comentario'),
            'lck_concurso' => Yii::t('app', 'Lck Concurso'),
            'fecha_sorteo_publicada' => Yii::t('app', 'Fecha Sorteo Publicada'),
            'fecha_entrevista_prueba_publicada' => Yii::t('app', 'Fecha Entrevista Prueba Publicada'),
            'estado_propuesta_jurados_preliminar' => Yii::t('app', 'Estado Propuesta Jurados Preliminar'),
            'estado_propuesta_preliminar' => Yii::t('app', 'Estado Propuesta Preliminar'),
            'firmantes_comision_seleccionados' => Yii::t('app', 'Firmantes Comision Seleccionados'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ConcursoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConcursoQuery(get_called_class());
    }

    public static function primaryKey()
    {
        return ['id_concurso'];
    }
}
