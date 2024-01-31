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
use app\models\TipoConcurso;
use app\models\ConcursoAsignatura;
use app\models\Asignatura;
use app\models\Categoria;
use app\models\Dedicacion;
use app\models\CargosActuales;
use DateTime;

/**
 * ProfileController implements the CRUD actions for Profile model.
 */
class MyPdf extends Fpdi {
    
    public function Header() {
        $width = $this->GetPageWidth('A4') - 20;
        $this->Image('images/formulario/header.png', 10, 8, $width);
        $this->SetY(45);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, $this->PageNo(), 0, 0, 'C');
    }
}

class ProfileController extends Controller
{
    public $provincias=['Buenos Aires','Ciudad Autónoma de Buenos Aires','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego, Antártida e Islas del Atlántico Sur','Tucumán'];

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

    public function actionEliminarcargo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $designacion = $_POST['designacion'];
            $categoria = $_POST['categoria'];
            $dedicacion = $_POST['dedicacion'];
            $asignatura = $_POST['asignatura'];
            $facultad = $_POST['facultad'];
            
            CargosActuales::deleteAll(['user_id' => Yii::$app->user->id, 'designacion' => $designacion, 'categoria' => $categoria, 'dedicacion' => $dedicacion, 'asignatura' => $asignatura, 'facultad' => $facultad]); 
        }
        return json_encode(['success' => true]);
    }

    public function actionAgregarcargo()
    {
        $cargo = new CargosActuales();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cargo->designacion = $_POST['designacion'];
            $cargo->categoria = $_POST['categoria'];
            $cargo->dedicacion = $_POST['dedicacion'];
            $cargo->asignatura = $_POST['asignatura'];
            $cargo->facultad = $_POST['facultad'];
            $cargo->user_id = Yii::$app->user->id;
        }
        $cargo->save(false);
        return json_encode(['success' => true]);
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
        // $provincias=['Buenos Aires','Ciudad Autónoma de Buenos Aires','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego, Antártida e Islas del Atlántico Sur','Tucumán'];
        $dataProvider = Profile::findOne(['user_id' => Yii::$app->user->id]);
        $concurso = Concurso::find()->where(['id_concurso' => $cid])->one();
        if ($this->request->isPost) 
        {
            if ($dataProvider->load($this->request->post())) 
            {
                $fecha_nacimiento_parts = explode('/', $dataProvider->nacimiento_fecha);
                if (count($fecha_nacimiento_parts) === 3) 
                {
                    $dataProvider->nacimiento_fecha = $fecha_nacimiento_parts[2] . '-' . $fecha_nacimiento_parts[1] . '-' . $fecha_nacimiento_parts[0];
                }
                if ($dataProvider->validate())
                {
                    if(isset($dataProvider->cid) && $dataProvider->cid != '0')
                    {
                        if(!$dataProvider->save(false)){
                            return $this->render('index?cid=0', [
                                'dataProvider' => $dataProvider,
                                'provincias' => $this->provincias
                            ]);  
                        };
                        if(!($this->preinscripcion($dataProvider->cid) && $this->previsualizar($dataProvider->cid)))
                        {
                            Yii::$app->session->setFlash('error', 'Error al preinscribirse.');
                            return $this->render('index?cid=0', [
                                'dataProvider' => $dataProvider,
                                'provincias' => $this->provincias
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
                            'provincias' => $this->provincias
                        ]);
                    }
                    else{
                        Yii::$app->session->setFlash('error', 'Error al guardar los datos.');
                        return $this->render('index', [
                            'dataProvider' => $dataProvider,
                            'provincias' => $this->provincias
                        ]);
                    }
                }

                else{
                    Yii::$app->session->setFlash('error', 'Error al guardar los datos.');
                    return $this->render('index', [
                        'dataProvider' => $dataProvider,
                        'provincias' => $this->provincias
                    ]);
                }
            }
            else
            {
                $errors = $dataProvider->getErrors();
                $errorMessage = "";
                if (is_array($errors) && !empty($errors)) {
                    $errorValue = reset($errors);
                    $errorMessage = $errorValue[0];
                }                
                Yii::$app->session->setFlash('error', $errorMessage);
                return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'provincias' => $this->provincias
                ]);            
            }
        } 

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'provincias' => $this->provincias,
            'cid' => $cid,
            'concurso' => $concurso
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
        $profile = Profile::findOne(['user_id' => Yii::$app->user->id]);
        $concurso = Concurso::find()->where(['id_concurso' => $id])->one();
        $preinscripto = new Preinscripto();
        $preinscripto->user_id = Yii::$app->user->id;
        $preinscripto->concurso_id = $id;

        $file = FileHelper::findFiles('attachments/formularios', [
            'only' => ['*' . $profile->cuil.'_' . $id . '*' . 'pdf'],
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
        $concurso = Concurso::findOne(['id_concurso' => $id]);
        // $facultad = ($concurso)?Facultad::findOne(['id_facultad' => $concurso->id_facultad]):$id;
        $facultad = Facultad::findOne(['id_facultad' => $concurso->id_facultad]);

            try
            {
                $adjuntos = FileHelper::findFiles('attachments/antecedentes', [
                    'only' => [$profile->cuil.'_' . '*' . 'pdf'],
                ]);
                $pdf = new MyPdf();
                $width = $pdf->GetPageWidth('A4') - 20;
                $lineHeight = 10; 

                $pdf->AddPage();


                // $pdf->Image('images/formulario/header.png', 10, 8, $width);
                // $pdf->SetY(45);
                
                $pdf->SetFont('Arial', '', 12, '', true, 'UTF-8');
                $encabezado = 'A partir del día de hoy ' . date('d/m/Y') . ' usted se encuentra preinscripto en estado pendiente al concurso';
                $pdf->Cell(40, 6, utf8_decode($encabezado), 0, 1);
                $encabezado = 'detallado a continuación. Recuerde que para que sea efectiva su Inscripción, debe dirigirse a la';
                $pdf->Cell(40, 6, utf8_decode($encabezado), 0, 1);
                $encabezado = 'plataforma TAD-UBA (en https://tramitesadistancia.uba.ar/ ) donde deberá anexar este formulario a la';
                $pdf->Cell(40, 6, utf8_decode($encabezado), 0, 1);
                $encabezado = 'documentación solicitada. Desde el ' . (new DateTime($concurso->fecha_inicio_inscripcion))->format('d/m/Y') . ' hasta el ' . (new DateTime($concurso->fecha_fin_inscripcion))->format('d/m/Y') . ' hasta las 18:00 de';
                $pdf->Cell(40, 6, utf8_decode($encabezado), 0, 1);
                $encabezado = 'la fecha del día de cierre. Este recibo de preinscripción NO habilita a la presentación.';
                $pdf->Cell(40, 6, utf8_decode($encabezado), 0, 1);
                

                $pdf->Cell(40, 6, '', 0, 1);
                $pdf->SetFont('Arial', 'B', 18);
                $pdf->SetFillColor(200, 200, 200);  
                $pdf->SetDrawColor(255, 255, 255);  
                $pdf->Cell(0, 10, 'DATOS DEL CONCURSO', 1, 1, 'L', 1);
                $pdf->Cell(40, 6, '', 0, 1);

                $pdf->SetFont('Arial', 'B', 13, '', true, 'UTF-8');
                
                $line = "Nº de expediente: $concurso->numero_expediente";
                $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');

                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 6, utf8_decode($line), 0, 'L');

                $data= TipoConcurso::find()->where(['id_tipo_concurso' => $concurso->id_tipo_concurso])->one()->descripcion_tipo_concurso;
                $line = "Tipo de concurso: $data";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 6, utf8_decode($line), 0, 'L');

                $data= Facultad::find()->where(['id_facultad' => $concurso->id_facultad])->one()->nombre_facultad;
                $line = "Unidad Académica: $data";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 6, utf8_decode($line), 0, 'L');

                $data= (AreaDepartamento::find()->where(['id_area_departamento' => $concurso->id_area_departamento]))&&AreaDepartamento::find()->where(['id_area_departamento' => $concurso->id_area_departamento])->one()->descripcion_area_departamento;
                $line = "Area: $data";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 6, utf8_decode($line), 0, 'L');

                
                try{
                    $concursoAsignaturas=ConcursoAsignatura::find()->where(['id_concurso' => $concurso->id_concurso])->all();
                    $idAsignaturaArray = [];
                    foreach ($concursoAsignaturas as $concursoAsignatura) {
                        if ($concursoAsignatura instanceof ConcursoAsignatura) {
                            $idAsignaturaArray[] = Asignatura::find()->where(['id_asignatura' => $concursoAsignatura->id_asignatura])->one()->descripcion_asignatura;
                        }
                    }
                } 
                catch(\Throwable $e){
                    $asignatura='';
                }
                

                // $id_asignatura=ConcursoAsignatura::find()->where(['id_concurso' => $concurso->id_concurso])->one()->id_asignatura;
                // $data= Asignatura::find()->where(['id_asignatura' => $id_asignatura])->one()->descripcion_asignatura;
                $line = "Asignatura: ".implode(' | ', $idAsignaturaArray);
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 6, utf8_decode($line), 0, 'L');

                $line = "Comentarios adicionales: $concurso->comentario";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 6, utf8_decode($line), 0, 'L');

                $data= Categoria::find()->where(['id_categoria' => $concurso->id_categoria])->one()->descripcion_categoria;
                $line = "Categoría: $data";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 6, utf8_decode($line), 0, 'L');

                $data= Dedicacion::find()->where(['id_dedicacion' => $concurso->id_dedicacion])->one()->descripcion_dedicacion;
                $line = "Dedicación: $data";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 6, utf8_decode($line), 0, 'L');
       
                $line = "Cantidad de cargos: $concurso->cantidad_de_puestos";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 6, utf8_decode($line), 0, 'L');

                $line = "Período de inscripción:";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 6, utf8_decode($line), 0, 'L');

                $concurso->fecha_inicio_inscripcion = DateTime::createFromFormat('Y-m-d H:i:s', $concurso->fecha_inicio_inscripcion)->format('d/m/Y H:i');

                $line = "Inicio inscripción: $concurso->fecha_inicio_inscripcion";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 6, utf8_decode($line), 0, 'L');

                $concurso->fecha_fin_inscripcion = DateTime::createFromFormat('Y-m-d H:i:s', $concurso->fecha_fin_inscripcion)->format('d/m/Y H:i');

                $line = "Fin inscripción: $concurso->fecha_fin_inscripcion";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 6, utf8_decode($line), 0, 'L');

                $pdf->SetFont('Arial', 'B', 18);
                $pdf->SetFillColor(200, 200, 200);  
                $pdf->SetDrawColor(255, 255, 255);  
                $pdf->Cell(40, 6, '', 0, 1);
                $pdf->Cell(0, 10, 'DATOS DEL ASPIRANTE', 1, 1, 'L', 1);
                $pdf->Cell(40, 6, '', 0, 1);

                $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');
                ($profile->numero_documento)&&$pdf->Cell(40, 6, 'Documento: '.$profile->numero_documento, 0, 1);                              
                ($profile->cuil)&&$pdf->Cell(40, 6, 'CUIL: '.$profile->cuil, 0, 1);                              
                ($profile->apellido)&&$pdf->Cell(40, 6, 'Apellido: '.$profile->apellido, 0, 1);                              
                ($profile->nombre)&&$pdf->Cell(40, 6, 'Nombre: '.$profile->nombre, 0, 1);    
                ($profile->numero_celular_sms)&&$pdf->Cell(40, 6, 'Telefono: '.$profile->numero_celular_sms, 0, 1);                              
                ($profile->email)&&$pdf->Cell(40, 6, 'Email: '.$profile->email, 0, 1); 

                $pdf->Cell(40, 6, '', 0, 1);
                $pdf->SetFont('Arial', 'B', 16, '', true, 'UTF-8');
                $pdf->Cell(40, 7, 'Datos Filiatorios', 0, 1,'',false); 
                $pdf->Cell(40, 6, '', 0, 1);

                $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');
                ($profile->estado_civil)&&$pdf->Cell(40, 7, 'Estado civil: '.$profile->estado_civil, 0, 1);                              
                ($profile->estado_civil != 'soltero')&&($profile->conyuge)&&$pdf->Cell(40, 7, 'Nombre y Apellido del Conyuge / Concubino: '.$profile->conyuge, 0, 1);                              
                ($profile->madre)&&$pdf->Cell(40, 7, 'Nombre y Apellido de la Madre: '.$profile->madre, 0, 1);                              
                ($profile->padre)&&$pdf->Cell(40, 7, 'Nombre y Apellido del Padre: '.$profile->padre, 0, 1);    
                
                $pdf->Cell(40, 6, '', 0, 1);
                $pdf->SetFont('Arial', 'B', 16, '', true, 'UTF-8');
                $pdf->Cell(40, 7, 'Lugar y Fecha de Nacimiento', 0, 1); 
                $pdf->Cell(40, 6, '', 0, 1);

                $fecha_nacimiento_parts = explode(" ", $profile->nacimiento_fecha)[0];
                $fecha_nacimiento_parts = explode('-', $profile->nacimiento_fecha);
                if (count($fecha_nacimiento_parts) === 3) {
                    $profile->nacimiento_fecha = $fecha_nacimiento_parts[2] . '/' . $fecha_nacimiento_parts[1] . '/' . $fecha_nacimiento_parts[0];
                }
                
                $nacimiento_expedido = (is_numeric($profile->nacimiento_expedido) && $profile->nacimiento_expedido >= 0 && $profile->nacimiento_expedido <= 23)?$this->provincias[$profile->nacimiento_expedido]:$profile->nacimiento_expedido;
                
                $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');
                ($profile->nacimiento_fecha)&&$pdf->Cell(40, 7, 'Fecha Nacimiento: '.$profile->nacimiento_fecha, 0, 1);                              
                ($profile->nacimiento_localidad)&&$pdf->Cell(40, 7, 'Localidad: '.$profile->nacimiento_localidad, 0, 1);                              
                ($profile->nacimiento_expedido)&&$pdf->Cell(40, 7, 'Provincia: '.$nacimiento_expedido, 0, 1);                              
                ($profile->nacimiento_pais)&&$pdf->Cell(40, 7, 'Pais: '.$profile->nacimiento_pais, 0, 1);  

                $pdf->Cell(40, 6, '', 0, 1);
                $pdf->SetFont('Arial', 'B', 16, '', true, 'UTF-8');
                $pdf->Cell(40, 7, 'Domicilio', 0, 1); 
                $pdf->Cell(40, 6, '', 0, 1);

                $domicilio_provincia = (is_numeric($profile->domicilio_provincia) && $profile->domicilio_provincia >= 0 && $profile->domicilio_provincia <= 23)?$this->provincias[$profile->domicilio_provincia]:$profile->domicilio_provincia;

                $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');
                ($profile->domicilio_calle)&&$pdf->Cell(40, 7, 'Calle: '.$profile->domicilio_calle, 0, 1);                              
                ($profile->domicilio_numero)&&$pdf->Cell(40, 7, 'Numero: '.$profile->domicilio_numero, 0, 1);                              
                ($profile->domicilio_piso)&&$pdf->Cell(40, 7, 'Piso: '.$profile->domicilio_piso, 0, 1);                              
                ($profile->domicilio_departamento)&&$pdf->Cell(40, 7, 'Departamento: '.$profile->domicilio_departamento, 0, 1);  
                ($profile->domicilio_codigo_postal)&&$pdf->Cell(40, 7, 'CP: '.$profile->domicilio_codigo_postal, 0, 1);                              
                ($profile->domicilio_localidad)&&$pdf->Cell(40, 7, 'Localidad: '.$profile->domicilio_localidad, 0, 1);                              
                ($profile->domicilio_provincia)&&$pdf->Cell(40, 7, 'Provincia: '.$domicilio_provincia, 0, 1);                              
                ($profile->domicilio_pais)&&$pdf->Cell(40, 7, 'Pais: '.$profile->domicilio_pais, 0, 1);  

                if (CargosActuales::find()->where(['user_id' => Yii::$app->user->id])->count() > 0) {
                    $cargosactuales=CargosActuales::find()->where(['user_id' => Yii::$app->user->id])->all(); 

                    // $pdf->AddPage();
                    $pdf->Cell(40, 6, '', 0, 1);
                    $pdf->SetFont('Arial', 'B', 18);
                    $pdf->SetFillColor(200, 200, 200);  
                    $pdf->SetDrawColor(255, 255, 255);  
                    $pdf->Cell(0, 10, 'CARGOS DOCENTES ACTUALES', 1, 1, 'L', 1);
                    $pdf->Cell(40, 6, '', 0, 1);
    
                    $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');

                    foreach ($cargosactuales as $cargoactual): 
                        $texto = "Designación: " . $cargoactual->designacion . " Categoría: " . $cargoactual->categoria . " Dedicación: " . $cargoactual->dedicacion . " Asignatura: " . $cargoactual->asignatura . " Facultad: " . $cargoactual->facultad; 
                        $lines = ceil($pdf->GetStringWidth($texto) / $width); 
                        $height = $lines * $lineHeight; 
                        ($texto)&&$pdf->MultiCell($width, 6, utf8_decode($texto), 0, 'L');
                    endforeach; 
    
                }

                $pdf->Cell(40, 6, '', 0, 1);
                $pdf->SetFont('Arial', 'B', 18);
                $pdf->SetFillColor(200, 200, 200);  
                $pdf->SetDrawColor(255, 255, 255);  
                $pdf->Cell(0, 10, 'ANTECEDENTES ACADEMICOS', 1, 1, 'L', 1);
                $pdf->Cell(40, 6, '', 0, 1);

                $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');
                ($profile->titulos_obtenidos)&&$pdf->MultiCell($width, 6, utf8_decode('TITULOS UNIVERSITARIOS OBTENIDOS: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->titulos_obtenidos) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->titulos_obtenidos)&&$pdf->MultiCell($width, 6, utf8_decode($profile->titulos_obtenidos), 0, 'L');

                ($profile->antecedentes_docentes)&&$pdf->MultiCell($width, 6, utf8_decode('ANTECEDENTES DOCENTES E INDOLE DE LAS TAREAS DESARROLLADAS: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->antecedentes_docentes) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->antecedentes_docentes)&&$pdf->MultiCell($width, 6, utf8_decode($profile->antecedentes_docentes), 0, 'L');

                ($profile->antecedentes_cientificos)&&$pdf->MultiCell($width, 6, utf8_decode('ANTECEDENTES CIENTÍFICOS, CONSIGNANDO LAS PUBLICACIONES: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->antecedentes_cientificos) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->antecedentes_cientificos)&&$pdf->MultiCell($width, 6, utf8_decode($profile->antecedentes_cientificos), 0, 'L');

                ($profile->cursos)&&$pdf->MultiCell($width, 6, utf8_decode('CURSOS DE ESPECIALIZACIÓN SEGUIDOS, CONFERENCIAS Y TRABAJOS DE INVESTIGACIÓN REALIZADOS SEAN ELLOS EDITOS O INEDITOS: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->cursos) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->cursos)&&$pdf->MultiCell($width, 6, utf8_decode($profile->cursos), 0, 'L');

                ($profile->congresos)&&$pdf->MultiCell($width, 6, utf8_decode('PARTICIPACIÓN EN CONGRESOS O ACONTECIMIENTOS SIMILARES NACIONALES O INTERNACIONALES: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->congresos) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->congresos)&&$pdf->MultiCell($width, 6, utf8_decode($profile->congresos), 0, 'L');

                ($profile->actuacion_universidades)&&$pdf->MultiCell($width, 6, utf8_decode('1- ACTUACIÓN EN UNIVERSIDADES E INSTITUTOS NACIONALES, PROVINCIALES Y PRIVADOS REGISTRADOS EN EL PAIS O EN EL EXTERIOR'), 0, 'L');
                ($profile->actuacion_universidades)&&$pdf->MultiCell($width, 6, utf8_decode('2- CARGOS QUE DESEMPEÑO O DESEMPEÑA EN LA ADMINISTRACIÓN PÚBLICA O EN LA ACTIVIDAD PRIVADA, EN EL PAIS O EN EL EXTRANJERO: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->actuacion_universidades) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->actuacion_universidades)&&$pdf->MultiCell($width, 6, utf8_decode($profile->actuacion_universidades), 0, 'L');

                ($profile->formacion_rrhh)&&$pdf->MultiCell($width, 6, utf8_decode('FORMACIÓN DE RECURSOS HUMANOS: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->formacion_rrhh) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->formacion_rrhh)&&$pdf->MultiCell($width, 6, utf8_decode($profile->formacion_rrhh), 0, 'L');

                ($profile->sintesis_aportes)&&$pdf->MultiCell($width, 6, utf8_decode('SÍNTESIS DE LOS APORTES ORIGINALES EFECTUADOS EN EL EJERCICIO DE LA ESPECIALIDAD RESPECTIVA: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->sintesis_aportes) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->sintesis_aportes)&&$pdf->MultiCell($width, 6, utf8_decode($profile->sintesis_aportes), 0, 'L');

                ($profile->sintesis_profesional)&&$pdf->MultiCell($width, 6, utf8_decode('SÍNTESIS DE LA ACTUACIÓN PROFESIONAL Y/O DE EXTENSIÓN UNIVERSITARIA: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->sintesis_profesional) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->sintesis_profesional)&&$pdf->MultiCell($width, 6, utf8_decode($profile->sintesis_profesional), 0, 'L');

                ($profile->otros_antecedentes)&&$pdf->MultiCell($width, 6, utf8_decode('OTROS ELEMENTOS DE JUICIO QUE CONSIDERE VALIOSOS: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->otros_antecedentes) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->otros_antecedentes)&&$pdf->MultiCell($width, 6, utf8_decode($profile->otros_antecedentes), 0, 'L');

                ($profile->labor_docente)&&$pdf->MultiCell($width, 6, utf8_decode('PLAN DE LABOR DOCENTE, DE INVESTIGACIÓN CIENTÍFICA Y TECNOLÓGICA Y DE EXTENSIÓN UNIVERSITARIA QUE, EN LÍNEAS GENERALES, DESARROLLARÁ EN CASO DE OBTENER EL CARGO CONCURSADO: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->labor_docente) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->labor_docente)&&$pdf->MultiCell($width, 6, utf8_decode($profile->labor_docente), 0, 'L');

                ($profile->renovacion)&&$pdf->MultiCell($width, 6, utf8_decode('INFORME DE LOS PROFESORES QUE RENUEVAN SOBRE EL CUMPLIMIENTO DEL PLAN DE ACTIVIDADES DOCENTES, DE INVESTIGACIÓN Y/O EXTENSIÓN PRESENTADO EN EL CONCURSO ANTERIOR: '), 0, 'L');
                $lines = ceil($pdf->GetStringWidth($profile->renovacion) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->renovacion)&&$pdf->MultiCell($width, 6, utf8_decode($profile->renovacion), 0, 'L');


                

                $pdf->Output('attachments/formularios/tmp.pdf', 'F');

                if (filesize('attachments/formularios/tmp.pdf') >= 50000000)
                {
                    Yii::$app->session->setFlash('error', 'El formulatio debe tener peso un máximo de 50MB');
                    return false;
                }
                $pdf->Output('attachments/formularios/Recibo_Preinscripcion-'.$profile->cuil.'_'.$id.'-'.$facultad->nombre_facultad.'-'.date('Ymd_His').'.pdf', 'F');
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
