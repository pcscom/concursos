<?php

namespace app\controllers;
use yii\filters\AccessControl;

use app\models\Concurso;
use app\models\ConcursoQuery;
use app\models\Facultad;
use app\models\Preinscripto;
use app\models\AreaDepartamento;
use app\models\Profile;
use app\models\ProfileQuery;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\Fpdf\Fpdf;
use yii\helpers\FileHelper;
use yii\helpers\Url;

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
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['confirmar','descargar','previsualizar','preinscripcion','index','view','create','update','delete','area','formulario','tramite','desinscribir'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['previsualizar','preinscripcion','index','view','create','update','delete','area','formulario','tramite'],
                            'allow' => false,
                            'roles' => ['?'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    private $pdf;
    public function __construct($id, $module, $config = [])
    {
        $this->pdf = new Fpdi();
        parent::__construct($id, $module, $config);
    }

    public function actionPrevisualizar()
    {
        $profile = Profile::findOne(['user_id' => Yii::$app->user->id]);
        if ($this->request->isPost) 
        {
            $id = $_POST['id'];

            try
            {
                $adjuntos = FileHelper::findFiles('attachments/antecedentes', [
                    'only' => ['*' . $profile->cuil.'_' . '*' . 'pdf'],
                ]);
                $pdf = new Fpdi();
                $width = $pdf->GetPageWidth('A4') - 20;
                $lineHeight = 10; // Altura de cada línea

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(40, 10, 'Formulario de inscripcion', 0, 1);

                $pdf->SetFont('Arial', 'B', 14, '', true, 'UTF-8');
                $pdf->Cell(40, 10, 'Datos del Aspirante', 0, 1);

                ($profile->user_id)&&$pdf->Cell(40, 10, 'userid: '.Yii::$app->user->id, 0, 1);     


                $pdf->SetFont('Arial', '', 12, '', true, 'UTF-8');
                ($profile->numero_documento)&&$pdf->Cell(40, 10, 'Documento: '.$profile->numero_documento, 0, 1);                              
                ($profile->cuil)&&$pdf->Cell(40, 10, 'CUIL: '.$profile->cuil, 0, 1);                              
                ($profile->apellido)&&$pdf->Cell(40, 10, 'Apellido: '.$profile->apellido, 0, 1);                              
                ($profile->nombre)&&$pdf->Cell(40, 10, 'Nombre: '.$profile->nombre, 0, 1);    
                ($profile->numero_celular_sms)&&$pdf->Cell(40, 10, 'Telefono: '.$profile->numero_celular_sms, 0, 1);                              
                ($profile->email)&&$pdf->Cell(40, 10, 'Email: '.$profile->email, 0, 1); 

                $pdf->SetFont('Arial', 'B', 14, '', true, 'UTF-8');
                $pdf->Cell(40, 10, 'Datos Filiatorios', 0, 1); 
                
                $pdf->SetFont('Arial', '', 12, '', true, 'UTF-8');
                ($profile->estado_civil)&&$pdf->Cell(40, 10, 'Estado civil: '.$profile->estado_civil, 0, 1);                              
                ($profile->conyuge)&&$pdf->Cell(40, 10, 'Nombre y Apellido del Conyuge / Concubino: '.$profile->conyuge, 0, 1);                              
                ($profile->madre)&&$pdf->Cell(40, 10, 'Nombre y Apellido de la Madre: '.$profile->madre, 0, 1);                              
                ($profile->padre)&&$pdf->Cell(40, 10, 'Nombre y Apellido del Padre: '.$profile->padre, 0, 1);    
                
                $pdf->SetFont('Arial', 'B', 14, '', true, 'UTF-8');
                $pdf->Cell(40, 10, 'Lugar y Fecha de Nacimiento', 0, 1); 
                
                $fecha_nacimiento_parts = explode('-', $profile->nacimiento_fecha);
                if (count($fecha_nacimiento_parts) === 3) {
                    $profile->nacimiento_fecha = $fecha_nacimiento_parts[2] . '/' . $fecha_nacimiento_parts[1] . '/' . $fecha_nacimiento_parts[0];
                }

                $pdf->SetFont('Arial', '', 12, '', true, 'UTF-8');
                ($profile->nacimiento_fecha)&&$pdf->Cell(40, 10, 'Fecha Nacimiento: '.$profile->nacimiento_fecha, 0, 1);                              
                ($profile->nacimiento_localidad)&&$pdf->Cell(40, 10, 'Localidad: '.$profile->nacimiento_localidad, 0, 1);                              
                ($profile->nacimiento_expedido)&&$pdf->Cell(40, 10, 'Autoridad de expedicion: '.$profile->nacimiento_expedido, 0, 1);                              
                ($profile->nacimiento_pais)&&$pdf->Cell(40, 10, 'Pais: '.$profile->nacimiento_pais, 0, 1);  

                $pdf->SetFont('Arial', 'B', 14, '', true, 'UTF-8');
                $pdf->Cell(40, 10, 'Domicilio', 0, 1); 

                $pdf->SetFont('Arial', '', 12, '', true, 'UTF-8');
                ($profile->domicilio_calle)&&$pdf->Cell(40, 10, 'Calle: '.$profile->domicilio_calle, 0, 1);                              
                ($profile->domicilio_numero)&&$pdf->Cell(40, 10, 'Numero: '.$profile->domicilio_numero, 0, 1);                              
                ($profile->domicilio_piso)&&$pdf->Cell(40, 10, 'Piso: '.$profile->domicilio_piso, 0, 1);                              
                ($profile->domicilio_departamento)&&$pdf->Cell(40, 10, 'Departamento: '.$profile->domicilio_departamento, 0, 1);  
                ($profile->domicilio_codigo_postal)&&$pdf->Cell(40, 10, 'CP: '.$profile->domicilio_codigo_postal, 0, 1);                              
                ($profile->domicilio_localidad)&&$pdf->Cell(40, 10, 'Localidad: '.$profile->domicilio_localidad, 0, 1);                              
                ($profile->domicilio_provincia)&&$pdf->Cell(40, 10, 'Provincia: '.$profile->domicilio_provincia, 0, 1);                              
                ($profile->domicilio_pais)&&$pdf->Cell(40, 10, 'Pais: '.$profile->domicilio_pais, 0, 1);  

                $pdf->SetFont('Arial', 'B', 14, '', true, 'UTF-8');
                $pdf->Cell(40, 10, 'Antecedentes', 0, 1); 
                $pdf->SetFont('Arial', '', 12, '', true, 'UTF-8');
                ($profile->titulos_obtenidos)&&$pdf->MultiCell($width, 10, utf8_decode('TITULOS UNIVERSITARIOS OBTENIDOS: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->titulos_obtenidos) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->titulos_obtenidos)&&$pdf->MultiCell($width, 10, utf8_decode($profile->titulos_obtenidos), 0, 'L');

                ($profile->antecedentes_docentes)&&$pdf->MultiCell($width, 10, utf8_decode('ANTECEDENTES DOCENTES E INDOLE DE LAS TAREAS DESARROLLADAS: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->antecedentes_docentes) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->antecedentes_docentes)&&$pdf->MultiCell($width, 10, utf8_decode($profile->antecedentes_docentes), 0, 'L');

                ($profile->antecedentes_cientificos)&&$pdf->MultiCell($width, 10, utf8_decode('ANTECEDENTES CIENTÍFICOS, CONSIGNANDO LAS PUBLICACIONES: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->antecedentes_cientificos) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->antecedentes_cientificos)&&$pdf->MultiCell($width, 10, utf8_decode($profile->antecedentes_cientificos), 0, 'L');

                ($profile->cursos)&&$pdf->MultiCell($width, 10, utf8_decode('CURSOS DE ESPECIALIZACIÓN SEGUIDOS, CONFERENCIAS Y TRABAJOS DE INVESTIGACIÓN REALIZADOS SEAN ELLOS EDITOS O INEDITOS: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->cursos) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->cursos)&&$pdf->MultiCell($width, 10, utf8_decode($profile->cursos), 0, 'L');

                ($profile->congresos)&&$pdf->MultiCell($width, 10, utf8_decode('PARTICIPACIÓN EN CONGRESOS O ACONTECIMIENTOS SIMILARES NACIONALES O INTERNACIONALES: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->congresos) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->congresos)&&$pdf->MultiCell($width, 10, utf8_decode($profile->congresos), 0, 'L');

                ($profile->actuacion_universidades)&&$pdf->MultiCell($width, 10, utf8_decode('1- ACTUACIÓN EN UNIVERSIDADES E INSTITUTOS NACIONALES, PROVINCIALES Y PRIVADOS REGISTRADOS EN EL PAIS O EN EL EXTERIOR'), 0, 'L');
                ($profile->actuacion_universidades)&&$pdf->MultiCell($width, 10, utf8_decode('2- CARGOS QUE DESEMPEÑO O DESEMPEÑA EN LA ADMINISTRACIÓN PÚBLICA O EN LA ACTIVIDAD PRIVADA, EN EL PAIS O EN EL EXTRANJERO: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->actuacion_universidades) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->actuacion_universidades)&&$pdf->MultiCell($width, 10, utf8_decode($profile->actuacion_universidades), 0, 'L');

                ($profile->formacion_rrhh)&&$pdf->MultiCell($width, 10, utf8_decode('FORMACIÓN DE RECURSOS HUMANOS: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->formacion_rrhh) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->formacion_rrhh)&&$pdf->MultiCell($width, 10, utf8_decode($profile->formacion_rrhh), 0, 'L');

                ($profile->sintesis_aportes)&&$pdf->MultiCell($width, 10, utf8_decode('SÍNTESIS DE LOS APORTES ORIGINALES EFECTUADOS EN EL EJERCICIO DE LA ESPECIALIDAD RESPECTIVA: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->sintesis_aportes) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->sintesis_aportes)&&$pdf->MultiCell($width, 10, utf8_decode($profile->sintesis_aportes), 0, 'L');

                ($profile->sintesis_profesional)&&$pdf->MultiCell($width, 10, utf8_decode('SÍNTESIS DE LA ACTUACIÓN PROFESIONAL Y/O DE EXTENSIÓN UNIVERSITARIA: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->sintesis_profesional) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->sintesis_profesional)&&$pdf->MultiCell($width, 10, utf8_decode($profile->sintesis_profesional), 0, 'L');

                ($profile->otros_antecedentes)&&$pdf->MultiCell($width, 10, utf8_decode('OTROS ELEMENTOS DE JUICIO QUE CONSIDERE VALIOSOS: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->otros_antecedentes) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->otros_antecedentes)&&$pdf->MultiCell($width, 10, utf8_decode($profile->otros_antecedentes), 0, 'L');

                ($profile->labor_docente)&&$pdf->MultiCell($width, 10, utf8_decode('PLAN DE LABOR DOCENTE, DE INVESTIGACIÓN CIENTÍFICA Y TECNOLÓGICA Y DE EXTENSIÓN UNIVERSITARIA QUE, EN LÍNEAS GENERALES, DESARROLLARÁ EN CASO DE OBTENER EL CARGO CONCURSADO: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->labor_docente) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->labor_docente)&&$pdf->MultiCell($width, 10, utf8_decode($profile->labor_docente), 0, 'L');

                ($profile->renovacion)&&$pdf->MultiCell($width, 10, utf8_decode('INFORME DE LOS PROFESORES QUE RENUEVAN SOBRE EL CUMPLIMIENTO DEL PLAN DE ACTIVIDADES DOCENTES, DE INVESTIGACIÓN Y/O EXTENSIÓN PRESENTADO EN EL CONCURSO ANTERIOR: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->renovacion) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->renovacion)&&$pdf->MultiCell($width, 10, utf8_decode($profile->renovacion), 0, 'L');

                // $pdf->SetLanguageArray('UTF-8');
                $pdf->Output('attachments/formularios/tmp.pdf', 'F');

                if (filesize('attachments/formularios/tmp.pdf') > 50000000)
                {
                    Yii::$app->session->setFlash('error', 'El formulatio debe tener peso un máximo de 50MB');
                    return true;
                }
                $pdf->Output('attachments/formularios/Recibo_Preinscripcion-'.$profile->cuil.'_'.$id.'.pdf', 'F');
            }
            catch(Exception $e)
            {
                Error($e->getMessage());
                Yii::$app->session->setFlash('error', 'Hubo un error al preinscribirse');
                return false;
            }
        }
        return false;
    }

    public function actionPreinscripcion($id)
    {
        $concurso = Concurso::find()->where(['id_concurso' => $id])->one();
        $preinscripto = new Preinscripto();
        $preinscripto->user_id = Yii::$app->user->id;
        $preinscripto->concurso_id = $id;
        $profile = Profile::findOne(['user_id' => Yii::$app->user->id]);

        $file = FileHelper::findFiles('attachments/formularios', [
            'only' => ['*' . $profile->cuil.'_' . $id . '*' . 'pdf'],
        ]);

        if ($this->request->isPost) 
        {
            if($preinscripto->save(false))
            {
                Yii::$app->session->setFlash('success', 'Se preinscribió correctamente');
                return true;
            }
            return false;
        }
        return $this->renderPartial('_preinscribirse', [
            'file' => basename($file[0]),
            'id' =>  $id
        ]);
    }

    public function actionConfirmar()
    {
        $id = Yii::$app->request->post('id');
        $preinscripto = new Preinscripto();
        $preinscripto->user_id = Yii::$app->user->id;
        $preinscripto->concurso_id = $id;

        if($preinscripto->save(false))
        {
            Yii::$app->session->setFlash('success', 'Se preinscribió correctamente');
            return true;
        }
        return false;
    }

    public function actionDescargar($ruta)
    {        
        $rutaCompleta = 'attachments/formularios/'.$ruta;
        // return($rutaCompleta);


        // if (file_exists($rutaCompleta)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($rutaCompleta) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($rutaCompleta));
            readfile($rutaCompleta);
            exit;
        // } 
    }

    public function actionDesinscribir()
    {
        if ($this->request->isPost) 
        {
            try{
                $pid = $_POST['pid'];

                $profile = Profile::findOne(['user_id' => Yii::$app->user->id]);
                $preinscripciones = Preinscripto::find()->where(['user_id' => Yii::$app->user->id, 'concurso_id' => $pid])->all(); 


                foreach ($preinscripciones as $preinscripcion) {
                    $concurso=Concurso::findOne(['id_concurso' => $preinscripcion->concurso_id]);
                    $facultad = Facultad::findOne(['id_facultad' => $concurso->id_facultad]);


                    $files = FileHelper::findFiles('attachments/formularios', [
                        'only' => ['*' . $profile->cuil.'_' . $concurso->id_concurso . '*' . 'pdf'],
                    ]);
                    foreach ($files as $file) {
                        FileHelper::unlink($file);
                    }

                    $preinscripcion->delete();

                }
                return json_encode(['success' => true]);
            }
            catch(Throwable $e){
                return json_encode(['error' => false]);
            }
        }
    }

    public function actionIndex($ua='%',$ar='%')
    {
        $profile = Profile::find(['user_id' => Yii::$app->user->id])->one();
        $searchModel = Concurso::find();
        $dataProvider = new ActiveDataProvider([
            'query' => Concurso::find(),
            'sort' => [
                'defaultOrder' => [
                    'id_concurso' => SORT_DESC,
                ]
            ],
            
        ]);
        $facultad = Facultad::find("id_facultad","nombre_facultad")->orderBy(['nombre_facultad' => SORT_ASC])->all();
        return $this->render('index', [
            'model' => $dataProvider,
            'searchModel' => $searchModel,
            'facultad' => $facultad,
            'ua' => $ua,
            'ar' => $ar,
            'profile' => $profile
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
        ->orderBy(['descripcion_area_departamento' => SORT_ASC])
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

    public function actionTramite($ua='%',$ar='%')
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
        $facultad = Facultad::find("id_facultad","nombre_facultad")->orderBy(['nombre_facultad' => SORT_ASC])->all();
        return $this->render('tramite', [
            'model' => $dataProvider,
            'searchModel' => $searchModel,
            'facultad' => $facultad,
            'ua' => $ua,
            'ar' => $ar
        ]);
    }
}
