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
    public $sessionKey = 'current_user';

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
    public $forgotpasswordPage = 'auth_forgotpassword';

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
    public $afterLoginPage = 'mystartup';

    /**
     * @var string
     */
    public $afterLogoutPage = '';

    /**
     * @var string
     */
    public $model  = 'Auth\\models\\User';

    public function signupAction()
    {
        $class = $this->model;
        $user = new $class();

        if (!empty($this->request->post)) {
            $user->setData($this->request->post);

            if ($user->valid()) {
                // $password = Password::generatePassword();
                $password = $user->password;
                $cryptedPassword = Password::crypt($password);
                $user->password = $cryptedPassword;

                $user->email_verification_code = Password::generateToken();
                $user->email_verification_date = date('Y-m-d H:i:s');
                $user->active = 0;

                if ($user->create((array)$user)) {
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
            'user' => $user,
            'pageTitle' => $this->pageTitle,
            'formAction' => Router::url($this->signupURL)
        ));
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
                $res = $query->executeAndFetch(array('idField' => $id));

                if ($res && Password::check($password, $res->password)) {
                    $class = $this->model;
                    $user = $class::findById($res->id);
                    Session::set($this->sessionKey, $user);
                    $this->redirect($this->afterLoginPage);
                } else {
                    Session::addFlash('Identifiant ou mot de passe incorrect', 'danger');
                }
            }
        }

        $params = array(
            'pageTitle'     => "Accédez à votre espace start-up",
            'formAction'    => Router::url($this->loginPage),
            'signupURL'     => "/jeparticipe",
            'errors'        => $errors
        );

        if (isset($id)) {
            $params[$this->idField] = $id;
        }

        $this->render('login', $params);
    }

    public function logoutAction()
    {
        Session::remove($this->sessionKey);
        Session::remove('fb_access_token');

        $this->redirect($this->afterLogoutPage);
    }

    public function forgotpasswordAction()
    {
        $post = $this->request->post;
        $errors = array();

        if (!empty($post) && isset($post['email'])) {
            $post['email'] = trim($post['email']);
            if ($post['email'] != '') {
                $class = $this->model;
                $user = $class::findByEmail($post['email']);
                if ($user !== null) {
                    $password = Password::generatePassword();
                    $user->password = Password::crypt($password);
                    $user->update(array(
                        'password' => $user->password
                    ));

                    $tpl =
                        '<html>'.
                            '<head>'.
                            '</head>'.
                            '<body>'.
                                '<p>'.
                                    'Identifiant : '.$user->email.'<br />'.
                                    'Nouveau mot de passe : '.$password.
                                '</p>'.
                            '</body>'.
                        '</html>';
                    $tpl = str_replace(array('{email}', '{password}'), array($user->email, $password), $tpl);


                    $email = new \PHPMailer();
                    $email->isMail();
                    $email->setFrom('contact@messageinawindow.com', 'Message In A Window');
                    $email->addAddress($user->email);
                    $email->addReplyTo('contact@messageinawindow.com', 'Message In A Window');
                    $email->CharSet = 'utf-8';
                    $email->isHTML(true);
                    $email->Subject = 'Votre nouveau mot de passe - Jeu-concours Message In A Window';
                    $email->Body = $tpl;
                    $email->AltBody = '';
                    if ($email->send()) {
                    } else {
                    }

                    Session::addFlash('Votre nouveau mot de passe vient de vous être envoyé par email', 'success');
                    $this->redirect($this->loginPage);
                } else {
                    $errors['email'] = 'Cet addresse email ne correspond à aucun compte';
                }
            } else {
                $errors['email'] = 'Email obligatoire';
            }
        }

        $params = array(
            'pageTitle' => 'Mot de passe oublié',
            'formAction' => Router::url($this->forgotpasswordPage),
            'errors' => $errors
        );
        $this->render('forgotpassword', $params);
    }
}
