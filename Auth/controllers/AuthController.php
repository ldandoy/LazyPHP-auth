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
     * @var mixed
     */
    public $connectedUser = null;

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
    public $loginPage = 'login';

    /**
     * @var string
     */
    public $loginPageTitle = 'Connexion';

    /**
     * @var string
     */
    public $afterloginPage = '';

    public function __construct($request)
    {
        parent::__construct($request);

        $this->connectedUser = Session::get('connectedUser');
    }

    /**
     * Check if use is connected
     * @return bool
     */
    protected function isConnected()
    {
        return $this->connectedUser !== null;
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
                    Session::set('connectedUser', $user);
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

    public function logoutAction()
    {
        Session::remove('connectedUser');
        $this->connectedUser = null;
        $this->redirect($this->loginPage);
    }
}
