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
    public $tableName = 'users';

    /**
     * @var string
     */
    public $connected = 'current_user';

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
    public $layout = 'login';

    /**
     * @var string
     */
    public $loginPage = 'auth_login';

    /**
     * @var string
     */
    public $signupURL = 'auth_signup';

    /**
     * @var string
     */
    public $pageTitle = 'Connexion au service';

    /**
     * @var string
     */
    public $afterLoginPage = '';

    /**
     * @var string
     */
    public $afterLogoutPage = '';


    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function loginAction()
    {
        $errors = array();
        $post = $this->request->post;
        
        if (!empty($post) && isset($post[$this->idField]) && isset($post[$this->passwordField])) {
            $id = trim($post[$this->idField]);
            $password = trim($post[$this->passwordField]);

            if ($id == '') {
                $errors[$this->idField] = 'Identifiant obligatoire';
            } else if (!filter_var($id, FILTER_VALIDATE_EMAIL)) {
                $errors[$this->idField] = 'Email invlaide';
            }

            if ($password == '') {
                $errors[$this->passwordField] = 'Mot de passe obligatoire';
            }

            if (empty($errors)) {
                $query = new Query();
                $query->select('*');
                $query->where($this->idField.' = :idField');
                $query->from($this->tableName);
                $connected = $query->executeAndFetch(array('idField' => $id));

                if ($connected && Password::check($password, $connected->password)) {
                    Session::set($this->connected, $connected);
                    $this->redirect($this->afterLoginPage);
                } else {
                    Session::addFlash('Identifiant ou mot de passe incorrect', 'danger');
                }
            }
        }

        $params = array(
            'pageTitle'     => $this->pageTitle,
            'formAction'    => Router::url($this->loginPage),
            'signupURL'     => Router::url($this->signupURL),
            'errors'        => $errors
        );

        if (isset($id)) {
            $params[$this->idField] = $id;
        }

        $this->render('login', $params);
    }

    public function signupAction()
    {
        $class = 'app\\models\\'.ucfirst($this->model);
        $connected = new $class();

        if (!empty($this->request->post)) {
            $connected->setData($this->request->post);

            if ($connected->valid()) {
                // $password = Password::generatePassword();
                $password = $connected->password;
                $cryptedPassword = Password::crypt($password);
                $connected->password = $cryptedPassword;

                $connected->email_verification_code = Password::generateToken();
                $connected->email_verification_date = date('Y-m-d H:i:s');
                $connected->active = 0;

                if ($connected->create((array)$connected)) {
                    Session::addFlash('Compte créé', 'success');
                    $this->redirect($this->afterSignupPage);
                } else {
                    Session::addFlash('Erreur insertion base de données', 'danger');
                };
            } else {
                Session::addFlash('Erreur(s) dans le formulaire', 'danger');
            }
        }

        $this->render('signup', array(
            'id' => 0,
            'user' => $connected,
            'pageTitle' => $this->pageTitle,
            'formAction' => Router::url($this->signupURL)
        ));
    }

    public function logoutAction()
    {
        Session::remove($this->connected);
        $this->redirect($this->afterLogoutPage);
    }
}
