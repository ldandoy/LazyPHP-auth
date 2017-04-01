<?php
/**
 * File Auth\controllers\AuthController.php
 *
 * @category System
 * @package  Netoverconsulting
 * @author   Loïc Dandoy <ldandoy@overconsulting.net>
 * @license  GNU
 * @link     http://overconsulting.net
 */

namespace Auth\controllers;

use System\Controller;
use System\Session;
use System\Query;
use System\Router;
use System\Password;
use app\models\User;

/**
 * Auth controller
 *
 * @category System
 * @package  Netoverconsulting
 * @author   Loïc Dandoy <ldandoy@overconsulting.net>
 * @license  GNU
 * @link     http://overconsulting.net
 */
class AuthController extends Controller
{
    /**
     * @var string
     */
    public $usersTable = 'users';

    /**
     * @var string
     */
    public $idField = 'email';

    /**
     * @var string
     */
    public $passwordField = 'password';

    /**
     * @var string
     */
    public $loginLayout = 'login';

    /**
     * @var string
     */
    public $loginPage = 'auth_auth_login';

    /**
     * @var string
     */
    public $loginPageTitle = 'Connexion';

    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function before()
    {
        if (AuthController::isConnected('connected')) {
            $this->redirect('/');
        }
    }

    /**
     * Check if use is connected
     * @return bool
     */
    public static function isConnected()
    {
        if (Session::get('connected')) {
            return true;
        }
        return false;
    }

    public function loginAction($goto = null)
    {

        $errors = array();
        $post = $this->request->post;
        $this->layout = $this->loginLayout;

        if (!empty($post) && isset($post[$this->idField]) && isset($post[$this->passwordField])) {
            $id = trim($post[$this->idField]);
            $password = trim($post[$this->passwordField]);

            if ($id == '') {
                $errors[$this->idField] = 'Champs obligatoire';
            } else if (!filter_var($id, FILTER_VALIDATE_EMAIL)) {
                $errors[$this->idField] = 'Email invlaide';
            }

            if ($password == '') {
                $errors[$this->passwordField] = 'Champs obligatoire';
            }

            if (empty($errors)) {
                $query = new Query();
                $query->select('*');
                $query->where($this->idField.' = :idField');
                $query->from($this->usersTable);
                $user = $query->executeAndFetch(array('idField' => $id));

                if ($user && Password::check($password, $user->password)) {
                    Session::set('connected', $user);
                    $this->redirect($this->afterLoginPage);
                } else {
                    Session::addFlash('Identifiant ou mot de passe incorrect', 'danger');
                }
            }
        }

        $params = array(
            'pageTitle' => $this->loginPageTitle,
            'formAction' => Router::url($this->loginPage),
            'errors' => $errors
        );

        if (isset($id)) {
            $params[$this->idField] = $id;
        }

        $this->render('login', $params);
    }

    public function signupAction()
    {
        $this->user = new User();

        if (!empty($this->request->post)) {
            $this->user->setData($this->request->post);

            if ($this->user->valid()) {
                // $password = Password::generatePassword();
                $password = $this->user->password;
                $cryptedPassword = Password::crypt($password);
                $this->user->password = $cryptedPassword;

                $this->user->email_verification_code = Password::generateToken();
                $this->user->email_verification_date = date('Y-m-d H:i:s');
                $this->user->active = 0;

                if ($this->user->create((array)$this->user)) {
                    Session::addFlash('Compte créé', 'success');
                    $this->redirect('auth_auth_login');
                } else {
                    Session::addFlash('Erreur insertion base de données', 'danger');
                };
            } else {
                Session::addFlash('Erreur(s) dans le formulaire', 'danger');
            }
        }

        $this->render('signup', array(
            'id' => 0,
            'user' => $this->user,
            'pageTitle' => 'Création de compte',
            'formAction' => Router::url('auth_auth_signup')
        ));
    }

    public function logoutAction()
    {

        Session::remove('connected');
        $this->redirect('');
    }
}
