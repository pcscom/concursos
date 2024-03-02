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
use app\models\Trato;
use app\models\PersonaConcursoRenovacion;
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
            try{
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
                            if(!($this->previsualizar($dataProvider->cid) && $this->preinscripcion($dataProvider->cid)))
                            {
                                Yii::$app->session->setFlash('error', 'Error al preinscribirse.');
                                return $this->render('index?cid=0', [
                                    'dataProvider' => $dataProvider,
                                    'provincias' => $this->provincias
                                ]);   
                            }
                            if(!$dataProvider->save(false)){
                                return $this->render('index?cid=0', [
                                    'dataProvider' => $dataProvider,
                                    'provincias' => $this->provincias
                                ]);  
                            };
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
                            Yii::$app->session->setFlash('error', 'Faltan cargar datos obligatorios o tienen un formato incorrecto.');
                            return $this->render('index', [
                                'dataProvider' => $dataProvider,
                                'provincias' => $this->provincias
                            ]);
                        }
                    }
    
                    else{
                        Yii::$app->session->setFlash('error', 'Faltan cargar datos obligatorios o tienen un formato incorrecto.');
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
            catch(\Throwable $e){
                Yii::$app->session->setFlash('error', 'Error al preinscribirse.');
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

                $line = 'A partir del día de hoy ' . date('d/m/Y') . ' usted se encuentra preinscripto en estado pendiente al concurso detallado a continuación. Recuerde que para que sea efectiva su Inscripción, debe dirigirse a la plataforma TAD-UBA (en https://tramitesadistancia.uba.ar/ ) donde deberá anexar este formulario a la documentación solicitada. Desde el ' . (new DateTime($concurso->fecha_inicio_inscripcion))->format('d/m/Y') . ' hasta el ' . (new DateTime($concurso->fecha_fin_inscripcion))->format('d/m/Y') . ' hasta las 18:00 de la fecha del día de cierre. Este recibo de preinscripción NO habilita a la presentación.';
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 7, utf8_decode($line), 0, 'L');


                $pdf->Cell(40, 12, '', 0, 1);
                $pdf->SetFont('Arial', 'B', 18);
                $pdf->SetFillColor(200, 200, 200);  
                $pdf->SetDrawColor(255, 255, 255);  
                $pdf->Cell(0, 12, 'DATOS DEL CONCURSO', 1, 1, 'L', 1);
                
                $line = "Nº de expediente: $concurso->numero_expediente";
                $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');
                $pdf->Cell(40, 5, '', 0, 1);

                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');

                $data= TipoConcurso::find()->where(['id_tipo_concurso' => $concurso->id_tipo_concurso])->one()->descripcion_tipo_concurso;
                $line = "Tipo de concurso: $data";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');

                $data= Facultad::find()->where(['id_facultad' => $concurso->id_facultad])->one()->nombre_facultad;
                $line = "Unidad Académica: $data";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');
                
                try{
                $data = (AreaDepartamento::find()->where(['id_area_departamento' => $concurso['id_area_departamento']])->andWhere(['id_facultad' => $concurso['id_facultad']])->one()->descripcion_area_departamento);
                $line = "Area: ".($data)?$dadta:"";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');
                } 
                catch(\Throwable $e){
                }
                
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
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');

                $line = "Comentarios adicionales: $concurso->comentario";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');

                $data= Categoria::find()->where(['id_categoria' => $concurso->id_categoria])->one()->descripcion_categoria;
                $line = "Categoría: $data";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');

                $data= Dedicacion::find()->where(['id_dedicacion' => $concurso->id_dedicacion])->one()->descripcion_dedicacion;
                $line = "Dedicación: $data";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');
       
                $line = "Cantidad de cargos: $concurso->cantidad_de_puestos";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');

                try
                {
                    $docentesqueocupancargo=PersonaConcursoRenovacion::find()->where(['id_concurso' => $concurso->id_concurso])->groupBy(['numero_documento'])->all();
                    $iddocenteArray = [];
                    $docentes="";
                    $primerDocente = true;
                    foreach ($docentesqueocupancargo as $docente) 
                    {
                        if ($docente instanceof PersonaConcursoRenovacion)
                        {
                            $perfilDocente = Profile::find()->where(['numero_documento' => $docente->numero_documento])->one();
                            if (!$perfilDocente) {
                                continue;
                            }  
                            $nombre = ($perfilDocente)?Profile::find()->where(['numero_documento' => $docente->numero_documento])->one()->nombre:'';
                            $apellido = ($perfilDocente)?Profile::find()->where(['numero_documento' => $docente->numero_documento])->one()->apellido:'';
                            $docentes .= (!$primerDocente)?", ":"";
                            $docentes .= ($perfilDocente)? $nombre." ".$apellido:'' ;
                            $primerDocente = false;
                        }
                        
                    }
                } 
                catch(\Throwable $e) {
                    $line = "";
                }
                $line = "Docente/s que ocupa/n cargo: ".$docentes;

                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');

                $line = "Período de inscripción:";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');

                $concurso->fecha_inicio_inscripcion = DateTime::createFromFormat('Y-m-d H:i:s', $concurso->fecha_inicio_inscripcion)->format('d/m/Y H:i');

                $line = "Inicio inscripción: $concurso->fecha_inicio_inscripcion";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');

                $concurso->fecha_fin_inscripcion = DateTime::createFromFormat('Y-m-d H:i:s', $concurso->fecha_fin_inscripcion)->format('d/m/Y H:i');

                $line = "Fin inscripción: $concurso->fecha_fin_inscripcion";
                $lines = ceil($pdf->GetStringWidth($line) / $width); 
                $height = $lines * $lineHeight;
                ($line)&&$pdf->MultiCell($width, 13, utf8_decode($line), 0, 'L');

                $pdf->SetFont('Arial', 'B', 18);
                $pdf->SetFillColor(200, 200, 200);  
                $pdf->SetDrawColor(255, 255, 255);  
                $pdf->Cell(40, 12, '', 0, 1);
                $pdf->Cell(0, 12, 'DATOS DEL ASPIRANTE', 1, 1, 'L', 1);

                $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');
                $pdf->Cell(40, 5, '', 0, 1);

                ($profile->numero_documento)&&$pdf->Cell(40, 12, utf8_decode('Documento: '.$profile->numero_documento), 0, 1);                              
                ($profile->cuil)&&$pdf->Cell(40, 12, utf8_decode('CUIL: '.$profile->cuil), 0, 1);                              
                ($profile->apellido)&&$pdf->Cell(40, 12, utf8_decode('Apellido: '.$profile->apellido), 0, 1);                              
                ($profile->nombre)&&$pdf->Cell(40, 12, utf8_decode('Nombre: '.$profile->nombre), 0, 1);    
                ($profile->sexo)&&$pdf->Cell(40, 12, utf8_decode('Sexo: '.$profile->sexo), 0, 1);    

                $trato = Trato::find()->where(['id_trato' => $profile->id_trato])->one();
                ($trato)&&$pdf->Cell(40, 12, utf8_decode('Trato: '.$trato->abreviatura_trato), 0, 1);   

                ($profile->numero_celular_sms)&&$pdf->Cell(40, 12, utf8_decode('Telefono: '.$profile->numero_celular_sms), 0, 1);                              
                ($profile->email)&&$pdf->Cell(40, 12, utf8_decode('Email: '.$profile->email), 0, 1); 

                $pdf->Cell(40, 12, '', 0, 1);
                $pdf->SetFont('Arial', 'B', 16, '', true, 'UTF-8');
                $pdf->Cell(40, 12, 'Datos Filiatorios', 0, 1,'',false); 
                // $pdf->Cell(40, 12, '', 0, 1);

                $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');
                ($profile->estado_civil)&&$pdf->Cell(40, 12, 'Estado civil: '.$profile->estado_civil, 0, 1);                              
                ($profile->estado_civil != 'soltero')&&($profile->conyuge)&&$pdf->Cell(40, 12, 'Nombre y Apellido del Conyuge / Concubino: '.$profile->conyuge, 0, 1);                              
                ($profile->madre)&&$pdf->Cell(40, 12, utf8_decode('Nombre y Apellido de la Madre: '.$profile->madre), 0, 1);                              
                ($profile->padre)&&$pdf->Cell(40, 12, utf8_decode('Nombre y Apellido del Padre: '.$profile->padre), 0, 1);    
                
                $pdf->Cell(40, 12, '', 0, 1);
                $pdf->SetFont('Arial', 'B', 16, '', true, 'UTF-8');
                $pdf->Cell(40, 12, 'Lugar y Fecha de Nacimiento', 0, 1); 
                // $pdf->Cell(40, 12, '', 0, 1);

                $fecha_nacimiento_parts = explode(" ", $profile->nacimiento_fecha)[0];
                $fecha_nacimiento_parts = explode('-', $profile->nacimiento_fecha);
                if (count($fecha_nacimiento_parts) === 3) {
                    $profile->nacimiento_fecha = $fecha_nacimiento_parts[2] . '/' . $fecha_nacimiento_parts[1] . '/' . $fecha_nacimiento_parts[0];
                }
                
                $nacimiento_expedido = (is_numeric($profile->nacimiento_expedido) && $profile->nacimiento_expedido >= 0 && $profile->nacimiento_expedido <= 23)?$this->provincias[$profile->nacimiento_expedido]:$profile->nacimiento_expedido;
                
                $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');
                ($profile->nacimiento_fecha)&&$pdf->Cell(40, 12, utf8_decode('Fecha Nacimiento: '.$profile->nacimiento_fecha), 0, 1);                              
                ($profile->nacimiento_localidad)&&$pdf->Cell(40, 12, utf8_decode('Localidad: '.$profile->nacimiento_localidad), 0, 1);                              
                ($profile->nacimiento_expedido)&&$pdf->Cell(40, 12, utf8_decode('Provincia: '.$nacimiento_expedido), 0, 1);                              
                ($profile->nacimiento_pais)&&$pdf->Cell(40, 12, utf8_decode('Pais: '.$profile->nacimiento_pais), 0, 1);  

                $pdf->Cell(40, 12, '', 0, 1);
                $pdf->SetFont('Arial', 'B', 16, '', true, 'UTF-8');
                $pdf->Cell(40, 12, 'Domicilio', 0, 1); 
                // $pdf->Cell(40, 12, '', 0, 1);

                $domicilio_provincia = (is_numeric($profile->domicilio_provincia) && $profile->domicilio_provincia >= 0 && $profile->domicilio_provincia <= 23)?$this->provincias[$profile->domicilio_provincia]:$profile->domicilio_provincia;

                $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');
                ($profile->domicilio_calle)&&$pdf->Cell(40, 12, utf8_decode('Calle: '.$profile->domicilio_calle), 0, 1);                              
                ($profile->domicilio_numero)&&$pdf->Cell(40, 12, utf8_decode('Numero: '.$profile->domicilio_numero), 0, 1);                              
                ($profile->domicilio_piso)&&$pdf->Cell(40, 12, utf8_decode('Piso: '.$profile->domicilio_piso), 0, 1);                              
                ($profile->domicilio_departamento)&&$pdf->Cell(40, 12, utf8_decode('Departamento: '.$profile->domicilio_departamento), 0, 1);  
                ($profile->domicilio_codigo_postal)&&$pdf->Cell(40, 12, utf8_decode('CP: '.$profile->domicilio_codigo_postal), 0, 1);                              
                ($profile->domicilio_localidad)&&$pdf->Cell(40, 12, utf8_decode('Localidad: '.$profile->domicilio_localidad), 0, 1);                              
                ($profile->domicilio_provincia)&&$pdf->Cell(40, 12, utf8_decode('Provincia: '.$domicilio_provincia), 0, 1);                              
                ($profile->domicilio_pais)&&$pdf->Cell(40, 12, utf8_decode('Pais: '.$profile->domicilio_pais), 0, 1);  

                if (CargosActuales::find()->where(['user_id' => Yii::$app->user->id])->count() > 0) {
                    $cargosactuales=CargosActuales::find()->where(['user_id' => Yii::$app->user->id])->all(); 

                    // $pdf->AddPage();
                    $pdf->Cell(40, 12, '', 0, 1);
                    $pdf->SetFont('Arial', 'B', 18);
                    $pdf->SetFillColor(200, 200, 200);  
                    $pdf->SetDrawColor(255, 255, 255);  
                    $pdf->Cell(0, 10, 'CARGOS DOCENTES ACTUALES', 1, 1, 'L', 1);
                    // $pdf->Cell(40, 12, '', 0, 1);
    
                    $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');


                    $pdf->SetLineWidth(0.4); // Ajusta según sea necesario
                    $pdf->SetDrawColor(255, 255, 255);
                    
                    $columnWidth = $width/5;
                    $columnWidths = [$columnWidth, $columnWidth, $columnWidth, $columnWidth, $columnWidth];

                    $pdf->SetFont('Arial', 'B', 13, '', true, 'UTF-8');

                    $pdf->Cell($columnWidths[0], 12, utf8_decode('Designación'), 1, 0, 'L', false);
                    $pdf->Cell($columnWidths[1], 12, utf8_decode('Categoría'), 1, 0, 'L', false);
                    $pdf->Cell($columnWidths[2], 12, utf8_decode('Dedicación'), 1, 0, 'L', false);
                    $pdf->Cell($columnWidths[3], 12, utf8_decode('Asignatura'), 1, 0, 'L', false);
                    $pdf->Cell($columnWidths[4], 12, utf8_decode('Facultad'), 1, 0, 'L', false);
                    $pdf->Ln();
                    
                    $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');

                    $pdf->SetDrawColor(0);
                    $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + $width, $pdf->GetY());
                    $pdf->SetDrawColor(255, 255, 255);
                    $pdf->SetFont('Arial', '', 12, '', true, 'UTF-8');    

                    $xPosition = $pdf->GetX();
                    $yPosition = $pdf->GetY();
                    foreach ($cargosactuales as $index => $cargoactual) {
                        $lines = ceil($pdf->GetStringWidth($cargoactual->designacion) / $columnWidths[0]); 
                        $height0 = $lines * 12; 
                        $lines = ceil($pdf->GetStringWidth($cargoactual->dedicacion) / $columnWidths[1]); 
                        $height1 = $lines * 12; 
                        $lines = ceil($pdf->GetStringWidth($cargoactual->asignatura) / $columnWidths[2]); 
                        $height2 = $lines * 12; 
                        $lines = ceil($pdf->GetStringWidth($cargoactual->facultad) / $columnWidths[3]); 
                        $height3 = $lines * 12; 
                        $lines = ceil($pdf->GetStringWidth($cargoactual->designacion) / $columnWidths[4]); 
                        $height4 = $lines * 12; 
                        $maxHeight = max($height0, $height1, $height2, $height3, $height4);

                        $pdf->SetX($xPosition);

                        $pdf->SetY($yPosition);

                        $pdf->MultiCell($columnWidths[0], 7, utf8_decode($cargoactual->designacion), 0, 'L', false);
                        // $pdf->MultiCell($columnWidths[0], 7, utf8_decode($pdf->GetX()), 0, 'L', false);

                        $pdf->SetY($yPosition);
                        $pdf->SetX($xPosition + $columnWidths[0]);
                    
                        $pdf->MultiCell($columnWidths[1], 7, utf8_decode($cargoactual->categoria), 0, 'L', false);
                        // $pdf->MultiCell($columnWidths[0], 7, utf8_decode($pdf->GetX()), 0, 'L', false);

                        $pdf->SetY($yPosition);
                        $pdf->SetX($xPosition + $columnWidths[0] + $columnWidths[1]);
                    
                        $pdf->MultiCell($columnWidths[2], 7, utf8_decode($cargoactual->dedicacion), 0, 'L', false);
                        // $pdf->MultiCell($columnWidths[0], 7, utf8_decode($pdf->GetX()), 0, 'L', false);

                        $pdf->SetY($yPosition);
                        $pdf->SetX($xPosition + $columnWidths[0] + $columnWidths[1] + $columnWidths[2]);
                    
                        $pdf->MultiCell($columnWidths[3], 7, utf8_decode($cargoactual->asignatura), 0, 'L', false);
                        // $pdf->MultiCell($columnWidths[0], 7, utf8_decode($pdf->GetX()), 0, 'L', false);

                        $pdf->SetY($yPosition);
                        $pdf->SetX($xPosition + $columnWidths[0] + $columnWidths[1] + $columnWidths[2] + $columnWidths[3]);
                    
                        $pdf->MultiCell($columnWidths[4], 7, utf8_decode($cargoactual->facultad), 0, 'L', false);
                        // $pdf->MultiCell($columnWidths[0], 7, utf8_decode($pdf->GetX()), 0, 'L', false);

                        $yPosition = $yPosition + 20;
                        // $pdf->Ln();
                    }


                    $pdf->SetFont('Arial', '', 13, '', true, 'UTF-8');    
                }

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 18);
                $pdf->SetFillColor(200, 200, 200);  
                $pdf->SetDrawColor(255, 255, 255);  
                $pdf->Cell(0, 13, 'ANTECEDENTES ACADEMICOS', 1, 1, 'L', 1);
                $pdf->Cell(40, 12, '', 0, 1);

                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('A.      TITULOS UNIVERSITARIOS OBTENIDOS '));
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('(indicando Facultad, Universidad que los otorgó. Los títulos universitarios no expedidos por esta Universidad deberán presentarse legalizados. La legalización se realiza en https://tramitesadistancia.uba.ar/ opción "Legalización de Títulos para Concursos UBA". De tratarse de títulos que no se encuentran en idioma español, deberá adjuntarse la debida traducción pública en el trámite de legalización.)'));
                $pdf->Cell(40, 10, '', 0, 1);
                $lines = ceil($pdf->GetStringWidth($profile->titulos_obtenidos) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->titulos_obtenidos)&&$pdf->MultiCell($width, 10, utf8_decode($profile->titulos_obtenidos), 0, 'L');
                
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('B.      ANTECEDENTES DOCENTES E INDOLE DE LAS TAREAS DESARROLLADAS '));
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('(indicando institución, período de ejercicio y naturaleza de su designación, lapso y lugar en que fueron realizados)'));
                $pdf->Cell(40, 10, '', 0, 1);
                $lines = ceil($pdf->GetStringWidth($profile->antecedentes_docentes) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->antecedentes_docentes)&&$pdf->MultiCell($width, 10, utf8_decode($profile->antecedentes_docentes), 0, 'L');

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('C.      ANTECEDENTES CIENTÍFICOS, CONSIGNANDO LAS PUBLICACIONES '));
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('(identificar a los autores, indicar editorial o revista, lugar y fecha de publicación, volumen, número y páginas) U OTROS RELACIONADOS CON LA ESPECIALIDAD (indicando lapso y lugar en que fueron realizados).'));
                $pdf->Cell(40, 10, '', 0, 1);
                $lines = ceil($pdf->GetStringWidth($profile->antecedentes_cientificos) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->antecedentes_cientificos)&&$pdf->MultiCell($width, 10, utf8_decode($profile->antecedentes_cientificos), 0, 'L');

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('D.      CURSOS DE ESPECIALIZACIÓN SEGUIDOS, CONFERENCIAS Y TRABAJOS DE INVESTIGACIÓN REALIZADOS SEAN ELLOS EDITOS O INEDITOS '));
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('(indicando lapso y lugar en que fueron realizados). Si se invocasen trabajos inéditos deberá presentar un ejemplar digitalizado por el aspirante, que será agregado al momento de confirmar la inscripción en https://tramitesadistancia.uba.ar/.'));
                $pdf->Cell(40, 10, '', 0, 1);
                $lines = ceil($pdf->GetStringWidth($profile->cursos) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->cursos)&&$pdf->MultiCell($width, 10, utf8_decode($profile->cursos), 0, 'L');

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('E.      PARTICIPACIÓN EN CONGRESOS O ACONTECIMIENTOS SIMILARES NACIONALES O INTERNACIONALES '));
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('(indicando lugar y lapso en que se realizaron y calidad de representación).'));
                $pdf->Cell(40, 10, '', 0, 1);
                $lines = ceil($pdf->GetStringWidth($profile->congresos) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->congresos)&&$pdf->MultiCell($width, 10, utf8_decode($profile->congresos), 0, 'L');

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('F.      1- ACTUACIÓN EN UNIVERSIDADES E INSTITUTOS NACIONALES, PROVINCIALES Y PRIVADOS REGISTRADOS EN EL PAIS O EN EL EXTERIOR '));
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('(indicando organismo o entidad, lugar y lapso)'));
                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->MultiCell($width, 10, utf8_decode(''), 0, 'L');
                $pdf->Write(7, utf8_decode('        2- CARGOS QUE DESEMPEÑO O DESEMPEÑA EN LA ADMINISTRACIÓN PÚBLICA O EN LA ACTIVIDAD PRIVADA, EN EL PAIS O EN EL EXTRANJERO '));
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('(indicando organismo o entidad, lugar y lapso)'));
                $pdf->Cell(40, 10, '', 0, 1);
                $lines = ceil($pdf->GetStringWidth($profile->actuacion_universidades) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->actuacion_universidades)&&$pdf->MultiCell($width, 10, utf8_decode($profile->actuacion_universidades), 0, 'L');

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('G.      FORMACIÓN DE RECURSOS HUMANOS '));
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('(indicando becas de instituciones acreditadas, tesinas, tesis, residencias, maestrías, etc.)'));
                $pdf->Cell(40, 10, '', 0, 1);
                $lines = ceil($pdf->GetStringWidth($profile->formacion_rrhh) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->formacion_rrhh)&&$pdf->MultiCell($width, 10, utf8_decode($profile->formacion_rrhh), 0, 'L');

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('H.      SÍNTESIS DE LOS APORTES ORIGINALES EFECTUADOS EN EL EJERCICIO DE LA ESPECIALIDAD RESPECTIVA '));
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('(indicando lapso y lugar en que fueron realizados; no se deben indicar los mencionados en apartados anteriores).'));
                $pdf->Cell(40, 10, '', 0, 1);
                $lines = ceil($pdf->GetStringWidth($profile->sintesis_aportes) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->sintesis_aportes)&&$pdf->MultiCell($width, 10, utf8_decode($profile->sintesis_aportes), 0, 'L');

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('I.      SÍNTESIS DE LA ACTUACIÓN PROFESIONAL Y/O DE EXTENSIÓN UNIVERSITARIA '));
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('(indicando lapso y lugar en que fueron realizados; no se deben indicar los mencionados en apartados anteriores).'));
                $pdf->Cell(40, 10, '', 0, 1);
                $lines = ceil($pdf->GetStringWidth($profile->sintesis_profesional) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->sintesis_profesional)&&$pdf->MultiCell($width, 10, utf8_decode($profile->sintesis_profesional), 0, 'L');

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('J.      OTROS ELEMENTOS DE JUICIO QUE CONSIDERE VALIOSOS '));
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->Write(7, utf8_decode('(indicando lapso y lugar en que fueron realizados; no se debe indicar los mencionados en apartados anteriores).'));
                $pdf->Cell(40, 10, '', 0, 1);
                $lines = ceil($pdf->GetStringWidth($profile->otros_antecedentes) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->otros_antecedentes)&&$pdf->MultiCell($width, 10, utf8_decode($profile->otros_antecedentes), 0, 'L');

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->MultiCell($width, 7, utf8_decode('K.      PLAN DE LABOR DOCENTE, DE INVESTIGACIÓN CIENTÍFICA Y TECNOLÓGICA Y DE EXTENSIÓN UNIVERSITARIA QUE, EN LÍNEAS GENERALES, DESARROLLARÁ EN CASO DE OBTENER EL CARGO CONCURSADO.'), 0, 'L');
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->MultiCell($width, 10, utf8_decode('        i.	Para profesores titulares y asociados: Forma en que desarrollará la enseñanza, sus puntos de vista sobre temas básicos de su campo de conocimiento que deben transmitirse a los alumnos; la importancia relativa y ubicación de su área en el currículo de la carrera. Medios que propone para mantener actualizada la enseñanza y para llevar a la práctica los cambios que sugiere.'), 0, 'L');
                $pdf->MultiCell($width, 10, utf8_decode('        ii.	Para profesor adjunto: Sus puntos de vista sobre temas básicos de su campo del conocimiento que deben transmitirse a los alumnos; la importancia relativa y ubicación de su área en el currículo de la carrera. Medios que propone para mantener actualizada la enseñanza y para llevar a la práctica los cambios que sugiere.'), 0, 'L');
                $pdf->Cell(40, 10, '', 0, 1);
                $lines = ceil($pdf->GetStringWidth($profile->labor_docente) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->labor_docente)&&$pdf->MultiCell($width, 10, utf8_decode($profile->labor_docente), 0, 'L');

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 10, '', true, 'UTF-8');
                $pdf->MultiCell($width, 10, utf8_decode('L.	    INFORME DE LOS PROFESORES QUE RENUEVAN SOBRE EL CUMPLIMIENTO DEL PLAN DE ACTIVIDADES DOCENTES, DE INVESTIGACIÓN Y/O EXTENSIÓN PRESENTADO EN EL CONCURSO ANTERIOR, ACOMPAÑADO DE LAS CERTIFICACIONES QUE CORRESPONDA'), 0, 'L');
                $pdf->SetFont('Arial', '', 10, '', true, 'UTF-8');
                $pdf->Cell(40, 10, '', 0, 1);
                $lines = ceil($pdf->GetStringWidth($profile->renovacion) / $width); 
                $height = $lines * $lineHeight; 
                ($profile->renovacion)&&$pdf->MultiCell($width, 10, utf8_decode($profile->renovacion), 0, 'L');


                

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
