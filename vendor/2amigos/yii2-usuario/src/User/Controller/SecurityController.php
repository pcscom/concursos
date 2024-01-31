<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\User\Controller;

use Da\User\Contracts\AuthClientInterface;
use Da\User\Event\FormEvent;
use Da\User\Event\UserEvent;
use Da\User\Form\LoginForm;
use Da\User\Model\User;
use Da\User\Query\SocialNetworkAccountQuery;
use Da\User\Service\SocialNetworkAccountConnectService;
use Da\User\Service\SocialNetworkAuthenticateService;
use Da\User\Traits\ContainerAwareTrait;
use Da\User\Traits\ModuleAwareTrait;
use Da\User\Validator\TwoFactorEmailValidator;
use Da\User\Validator\TwoFactorTextMessageValidator;
use Yii;
use yii\authclient\AuthAction;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use Da\User\Model\Profile;
use Da\User\Query\UserQuery;
use Da\User\Model\Passwordchange;
use Da\User\Helper\SecurityHelper;
use yii\helpers\Url;

class SecurityController extends Controller
{
    use ContainerAwareTrait;
    use ModuleAwareTrait;

    protected $socialNetworkAccountQuery;

    /**
     * SecurityController constructor.
     *
     * @param string                    $id
     * @param Module                    $module
     * @param SocialNetworkAccountQuery $socialNetworkAccountQuery
     * @param array                     $config
     */
    public function __construct(
        $id,
        Module $module,
        SocialNetworkAccountQuery $socialNetworkAccountQuery,
        array $config = []
    ) {
        $this->socialNetworkAccountQuery = $socialNetworkAccountQuery;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'confirm', 'auth'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login', 'auth', 'logout', 'password'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                // if user is not logged in, will try to log him in, otherwise
                // will try to connect social account to user.
                'successCallback' => Yii::$app->user->isGuest
                    ? [$this, 'authenticate']
                    : [$this, 'connect'],
            ],
        ];
    }

    /**
     * Controller action responsible for handling login page and actions.
     *
     * @throws InvalidConfigException
     * @throws InvalidParamException
     * @return array|string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->getIsGuest()) {
            return $this->goHome();
        }

        /**
        * @var LoginForm $form
        */
        $form = $this->make(LoginForm::class);

        /**
        * @var FormEvent $event
        */
        $event = $this->make(FormEvent::class, [$form]);

        if (Yii::$app->request->isAjax && $form->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $errors = ActiveForm::validate($form);
            if (empty($errors)) {
                return $errors;
            }
            $this->trigger(FormEvent::EVENT_FAILED_LOGIN, $event);
            return $errors;
        }

        if ($form->load(Yii::$app->request->post())) {
            if ($this->module->enableTwoFactorAuthentication && $form->validate()) {
                $user = $form->getUser();

                if ($user->auth_tf_enabled) {
                    Yii::$app->session->set('credentials', ['login' => $form->login, 'pwd' => $form->password]);
                    return $this->redirect(['confirm']);
                }
            }

            $this->trigger(FormEvent::EVENT_BEFORE_LOGIN, $event);
            if ($form->login()) {
                $form->getUser()->updateAttributes([
                    'last_login_at' => time(),
                    'last_login_ip' => $this->module->disableIpLogging ? '127.0.0.1' : Yii::$app->request->getUserIP(),
                ]);

                $this->trigger(FormEvent::EVENT_AFTER_LOGIN, $event);

                return $this->goBack();
            }
            $this->trigger(FormEvent::EVENT_FAILED_LOGIN, $event);
        }

        return $this->render(
            'login',
            [
                'model' => $form,
                'module' => $this->module,
            ]
        );
    }

    public function actionConfirm()
    {
        if (!Yii::$app->user->getIsGuest()) {
            return $this->goHome();
        }

        if (!Yii::$app->session->has('credentials')) {
            return $this->redirect(['login']);
        }

        $credentials = Yii::$app->session->get('credentials');
        /**
        * @var LoginForm $form
        */
        $form = $this->make(LoginForm::class);
        $form->login = $credentials['login'];
        $form->password = $credentials['pwd'];
        $form->setScenario('2fa');

        /**
        * @var FormEvent $event
        */
        $event = $this->make(FormEvent::class, [$form]);

        if (Yii::$app->request->isAjax && $form->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($form);
        }

        if ($form->load(Yii::$app->request->post())) {
            $this->trigger(FormEvent::EVENT_BEFORE_LOGIN, $event);

            if ($form->login()) {
                Yii::$app->session->set('credentials', null);

                $form->getUser()->updateAttributes(['last_login_at' => time()]);

                $this->trigger(FormEvent::EVENT_AFTER_LOGIN, $event);

                return $this->goBack();
            }
        } else {
            $module = Yii::$app->getModule('user');
            $validators = $module->twoFactorAuthenticationValidators;
            $credentials = Yii::$app->session->get('credentials');
            $login = $credentials['login'];
            $user = User::findOne(['email' => $login]);
            if ($user == null) {
                $user = User::findOne(['username' => $login]);
            }
            $tfType = $user->getAuthTfType();

            $class = ArrayHelper::getValue($validators, $tfType.'.class');
            $object = $this
                ->make($class, [$user, null, $this->module->twoFactorAuthenticationCycles]);

            $object->generateCode();
        }

        return $this->render(
            'confirm',
            [
                'model' => $form,
                'module' => $this->module
            ]
        );
    }

    public function actionLogout()
    {
        $event = $this->make(UserEvent::class, [Yii::$app->getUser()->getIdentity()]);

        $this->trigger(UserEvent::EVENT_BEFORE_LOGOUT, $event);

        if (Yii::$app->getUser()->logout()) {
            $this->trigger(UserEvent::EVENT_AFTER_LOGOUT, $event);
        }

        return $this->goHome();
    }

    public function authenticate(AuthClientInterface $client)
    {
        $this->make(SocialNetworkAuthenticateService::class, [$this, $this->action, $client])->run();
    }

    public function connect(AuthClientInterface $client)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('danger', Yii::t('usuario', 'Something went wrong'));

            return;
        }

        $this->make(SocialNetworkAccountConnectService::class, [$this, $client])->run();
    }

    public function actionPassword()
    {
        $profile = Profile::findOne(['user_id' => Yii::$app->user->id]);


        if ($profile === null) {
            $profile = $this->make(Profile::class);
            $profile->link('user', Yii::$app->user->identity);
        }

        /** @var ProfileEvent $event */
        // $event = $this->make(ProfileEvent::class, [$profile]);

        // $this->make(AjaxRequestModelValidator::class, [$profile])->validate();


        $user = User::findOne(Yii::$app->user->identity->getId());

        if ($profile === null) {
            throw new NotFoundHttpException();
        }

        //if ($profile->load(Yii::$app->request->post()) && $profile->save()) {}
        
        if (Yii::$app->request->post()) 
        {
            $data=Yii::$app->request->post();
            if (($data ["Passwordchange"] ["oldpass"] == '')||(!Yii::$app->getSecurity()->validatePassword($data ["Passwordchange"] ["oldpass"], $user->password_hash))) 
            {
                Yii::$app->getSession()->setFlash('danger','La contraseña actual es inválida');
            }
            elseif (($data ["Passwordchange"] ["newpass"]) != ($data ["Passwordchange"] ["newpassagain"]))
            {
                Yii::$app->getSession()->setFlash('danger','Las contraseñas nuevas no coinciden');
            }
            elseif (strlen($data ["Passwordchange"] ["newpass"]) < 8)
            {
                Yii::$app->getSession()->setFlash('danger','Las contraseña debe tener al menos 8 caracteres');
            }
            else //cambio de password
            {

                //envio por mail
                // list($enviar) = Yii::$app->createController('correos'); 
                // $enviar->nuevapassword($profile->firstname,$user->email,$data ["Passwordchange"] ["newpassagain"]);

                $user->password_hash = $data ["Passwordchange"] ["newpassagain"];
                $security = $this->make(SecurityHelper::class);
                $user->auth_key = $security->generateRandomString();
                $user->registration_ip = Yii::$app->request->getUserIP();
                $user->password_hash = $security->generatePasswordHash($data ["Passwordchange"] ["newpassagain"], $this->getModule()->blowfishCost);
                $user->password_changed_at = time();
                $user->save();
                // Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                //     [
                //         'text' => 'La contraseña fue modificada con éxito',
                //         'confirmButtonText' => 'Entendido',
                //      ]]);
                //logout
                // Yii::$app->user->logout();
                return Yii::$app->response->redirect(Url::base().'/user/login');
            }
        }

        $pass = new Passwordchange;
        return $this->render(
            'password',
            [
                'model' => $profile,
                'user' => $user,
                'pass'=> $pass
            ]
        );
    }
}
