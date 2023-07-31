<?php

namespace app\controllers;
use yii\filters\AccessControl;
use app\models\Profile;
use app\models\ProfileQuery;
use app\models\Adjuntos;
use app\models\AdjuntosQuery;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use Yii;
use yii\bootstrap5\ActiveForm;
use kartik\file\FileInput;
use yii\web\UploadedFile;
use app\models\Concurso;
use app\models\ConcursoQuery;
use app\models\Facultad;
use app\models\Preinscripto;
use app\models\AreaDepartamento;
use yii\helpers\Json;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\Fpdf\Fpdf;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use Da\User\Model\Passwordchange;

/**
 * ProfileController implements the CRUD actions for Profile model.
 */
class ProfileController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Profile models.
     *
     * @return string
     */

    public function actionDelete()
    {
        if ($this->request->isPost) 
        {
            $doc = $_POST['doc'];
            $adjunto = ADJUNTOS::findOne(['user_id' => Yii::$app->user->id, 'nombre' => $doc]); 
            if($adjunto->delete(false))
            {
                return json_encode(['success' => true]);
            }
        }
        return json_encode(['error' => false]);
    }
 
    public function actionUpload($tipo)
    {
        if ($this->request->isPost) 
        {
            $adjuntos = new Adjuntos();
            $model = new Adjuntos();
            $adjuntos->nombre = UploadedFile::getInstance($adjuntos, 'nombre');  
            if (substr($adjuntos->nombre, -4) !== ".pdf") {
                Yii::$app->session->setFlash('error', 'El archivo debe ser de tipo PDF');
                echo json_encode(false); // return json data
                return(false);
            }   
            $path = 'attachments/antecedentes/' . Yii::$app->user->id . "_" .$adjuntos->nombre;
            // $model->url = $path;     
            $model->tamano = $adjuntos->nombre->size; 
            $model->tipo=$tipo;
            $model->user_id=Yii::$app->user->id;
            if($adjuntos->nombre->saveAs($path))
            {
                $model->nombre = $adjuntos->nombre->name;
                $model->validate();
                $model->save();
                if ($model->save()) 
                {
                    Yii::$app->session->setFlash('success', 'El archivo se guardó correctamente');
                    echo json_encode(true); // return json data
                    return(false);
                }
            }
            
        }
        Yii::$app->session->setFlash('error', 'Ha ocurrido un error al subir el archivo');
        echo json_encode(false);
        return(false);
    }


    public function actionIndex($cid=0)
    {        
        $dataProvider = Profile::findOne(['user_id' => Yii::$app->user->id]);

        if ($this->request->isPost) 
        {
            if ($dataProvider->load($this->request->post())) 
            {
                if(isset($dataProvider->cid) && $dataProvider->cid !== 0)
                {
                    $dataProvider->save(false);
                    if(!($this->preinscripcion($dataProvider->cid) && $this->previsualizar($dataProvider->cid)))
                    {
                        Yii::$app->session->setFlash('error', 'Error al preinscribirse.');
                        return $this->render('index', [
                            'dataProvider' => $dataProvider,
                        ]);   
                    }
                    Yii::$app->session->setFlash('success', 'Se preinscribió correctamente');
                    return $this->redirect(['/concurso']);
                }
                if($dataProvider->save(false))
                {
                    Yii::$app->session->setFlash('success', 'Perfil actualizado!');
                    return $this->render('index', [
                        'dataProvider' => $dataProvider,
                    ]);
                }
                else{
                    Yii::$app->session->setFlash('error', 'Error al guardar los datos.');
                    return $this->render('index', [
                        'dataProvider' => $dataProvider,
                    ]);
                }
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Error al guardar los datos.');
                return $this->render('index', [
                    'dataProvider' => $dataProvider,
                ]);            
            }
        } 

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'cid' => $cid
        ]);
    }

    /**
     * Displays a single Profile model.
     * @param int $user_id Id Profile
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($user_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id),
        ]);
    }

    /**
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $user_id Id Profile
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($user_id)
    {
        $model = $this->findModel($user_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $user_id Id Profile
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id)
    {
        if (($model = Profile::findOne(['user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function preinscripcion($id)
    {
        $concurso = Concurso::find()->where(['id_concurso' => $id])->one();
        $preinscripto = new Preinscripto();
        $preinscripto->user_id = Yii::$app->user->id;
        $preinscripto->concurso_id = $id;

        $file = FileHelper::findFiles('attachments/formularios', [
            'only' => [Yii::$app->user->id.'_' . $id . '*' . 'pdf'],
        ]);

        if ($this->request->isPost) 
        {
            if($preinscripto->save(false))
            {
                return true;
            }
            return false;        
        }
        return true;
    }

    public function previsualizar($id)
    {
        $profile = Profile::findOne(['user_id' => Yii::$app->user->id]);
            try
            {
                $adjuntos = FileHelper::findFiles('attachments/antecedentes', [
                    'only' => [Yii::$app->user->id.'_' . '*' . 'pdf'],
                ]);
                $pdf = new Fpdi();
                $width = $pdf->GetPageWidth('A4') - 20;
                $lineHeight = 10; 

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(40, 10, 'Formulario de inscripcion', 0, 1);

                $pdf->SetFont('Arial', 'B', 14, '', true, 'UTF-8');
                $pdf->Cell(40, 10, 'Datos del Aspirante', 0, 1);

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

                $pdf->Output('attachments/formularios/tmp.pdf', 'F');

                if (filesize('attachments/formularios/tmp.pdf') >= 50000000)
                {
                    Yii::$app->session->setFlash('error', 'El formulatio debe tener peso un máximo de 50MB');
                    return false;
                }
                $pdf->Output('attachments/formularios/'.Yii::$app->user->id.'_'.$id.'.pdf', 'F');
                return true;
            }
            catch(Exception $e)
            {
                Error($e->getMessage());
                return false;
            }
        return false;
    }

}
