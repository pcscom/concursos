<?php

namespace app\controllers;

use app\models\Concurso;
use app\models\ConcursoQuery;
use app\models\Facultad;
use app\models\AreaDepartamento;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
/**
 * ConcursoController implements the CRUD actions for Concurso model.
 */
class ConcursoController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Concurso models.
     *
     * @return string
     */
    public function actionIndex($ua='%',$ar='%')
    {
        $searchModel = Concurso::find();
        $dataProvider = new ActiveDataProvider([
            'query' => Concurso::find(),
            'sort' => [
                'defaultOrder' => [
                    'id_concurso' => SORT_DESC,
                ]
            ],
            
        ]);
        $facultad = Facultad::find("id_facultad","nombre_facultad")->all();
        return $this->render('index', [
            'model' => $dataProvider,
            'searchModel' => $searchModel,
            'facultad' => $facultad,
            'ua' => $ua,
            'ar' => $ar
        ]);
    }

    /**
     * Displays a single Concurso model.
     * @param int $id_concurso Id Concurso
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_concurso)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_concurso),
        ]);
    }

    /**
     * Creates a new Concurso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Concurso();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_concurso' => $model->id_concurso]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Concurso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_concurso Id Concurso
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_concurso)
    {
        $model = $this->findModel($id_concurso);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_concurso' => $model->id_concurso]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Concurso model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_concurso Id Concurso
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_concurso)
    {
        $this->findModel($id_concurso)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Concurso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_concurso Id Concurso
     * @return Concurso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_concurso)
    {
        if (($model = Concurso::findOne(['id_concurso' => $id_concurso])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionArea()
    {
        $parameter = Yii::$app->request->post('facultad');
        $area = AreaDepartamento::find()
        ->where(['id_facultad' => $parameter])
        ->all();
        return Json::encode($area);
    }

    public function actionFormulario($id)
    {
        $dataProvider = Concurso::find()->where(['id_concurso' => $id])->one();
        return $this->renderPartial('_formulario', [
            'model' => $dataProvider,
        ]);
    }
}
