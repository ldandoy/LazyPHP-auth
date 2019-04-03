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

use Core\Controller;
use Core\Session;
use Core\Query;
use Core\Router;
use Core\Password;
use Core\Mail;

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
    public $loginPage = 'usersauth_login';

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
    public $afterLoginPage = 'user';

    /**
     * @var string
     */
    public $afterSignupPage = '/';

    /**
     * @var string
     */
    public $afterLoginPageCokpit = 'cockpit';

    /**
     * @var string
     */
    public $afterLogoutPage = '';

    public $sponsorship = false;

    public $redirect = true;

    public function signupAction()
    {
        $userClass = $this->loadModel('User');
        $user = new $userClass();

        if (!empty($this->request->post)) {
            if ($this->request->post['password'] == $this->request->post['password2']) {
                $user->setData($this->request->post);

                if ($user->valid()) {
                    // $password = Password::generatePassword();
                    $password = $user->password;
                    $cryptedPassword = Password::crypt($password);
                    $user->password = $cryptedPassword;

                    $user->email_verification_code = Password::generateToken();
                    $user->email_verification_date = date('Y-m-d H:i:s');
                    $user->active = 1;
                    $user->group_id = 2;
                    $user->site_id = $this->site->id;

                    if ($this->sponsorship) {
                        $user->sponsorship = uniqid();
                    }
                    $toto = $user->create((array)$user);
                    if ($toto) {
                        $this->addFlash('Compte créé', 'success');
                        $user->id = $toto;
                        $this->session->set($this->sessionKey, $user);
                        
                        if (isset($this->request->post["return"]) && $this->request->post["return"] != '') {
                            $this->afterSignupPage = $this->request->post["return"];
                        }

                        $this->redirect($this->afterSignupPage);

                    } else {
                        $this->addFlash('Erreur insertion base de données', 'danger');
                    };
                } else {
                    $this->addFlash('Erreur(s) dans le formulaire', 'danger');
                }
            } else {
                $this->addFlash('Erreur(s) dans les mots de passe', 'danger');
            }
        }
        $this->render(
            'auth::signup',
            array(
                'id' => 0,
                'user' => $user,
                'pageTitle' => 'Créez votre compte',
                'altImageLogin' => 'Default Image Login',
                'imageLogin' => $this->site->brand_logo->url,
                'formAction' => Router::url($this->signupURL)
            )
        );
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
                $errors[$this->idField] = 'Email invalide';
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
                    if ($res->active != 1) {
                        $this->addFlash("Ce compte n'est pas activé", 'danger');
                    } else {
                        $userClass = $this->loadModel('User');
                        $user = $userClass::findById($res->id);
                        $this->session->set($this->sessionKey, $user);
                        if ($user->group->cockpit == 1) {
                            $this->redirect($this->afterLoginPageCokpit);
                        } else {
                            $this->redirect($this->afterLoginPage);
                        }
                    }
                } else {
                    $this->addFlash('Identifiant ou mot de passe incorrect', 'danger');
                }
            }
        }

        $this->params = array(
            'pageTitle'     => 'Accédez à votre espace',
            'formAction'    => Router::url($this->loginPage),
            'formAction2'   => Router::url($this->loginPage),
            'signupURL'     => '/users',
            'imageLogin'    => $this->site->brand_logo->url,
            'altImageLogin' => 'Default Image Login',
            'errors'        => $errors
        );

        if (isset($id)) {
            $this->params[$this->idField] = $id;
        }
    }

    public function logoutAction()
    {
        $this->session->remove($this->sessionKey);
        $this->session->remove('site');
        $this->session->remove('fb_access_token');

        $this->redirect($this->afterLogoutPage);
    }

    public function forgotpasswordAction()
    {
        $post = $this->request->post;
        $errors = array();

        if (!empty($post) && isset($post['email'])) {
            $post['email'] = trim($post['email']);
            if ($post['email'] != '') {
                $userClass = $this->loadModel('User');
                $user = $userClass::findByEmail($post['email']);
                if ($user !== null) {
                    $password = Password::generatePassword();
                    $user->password = Password::crypt($password);
                    $user->update(array(
                        'password' => $user->password
                    ));

                    /*$tpl =
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
                    $email->setFrom(MAIL_SENDER, 'CE');
                    $email->addAddress($user->email);
                    $email->addReplyTo(MAIL_SENDER, 'CE');
                    $email->CharSet = 'utf-8';
                    $email->isHTML(true);
                    $email->Subject = 'Votre nouveau mot de passe';
                    $email->Body = $tpl;
                    $email->AltBody = '';
                    if ($email->send()) {
                    } else {
                    }*/

                    $contents=  '
                        <body>
                            <table>
                                <tr>
                                    <td><h1>Voici vos nouveaux accès au Bureau Virtuel</h1></td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>URL:</b> <a href="http://'.$this->site->host.'" target="_blank">http://'.$this->site->host.'</a><br />
                                        <b>Login:</b> '.$user->email.'<br />
                                        <b>Mot de passe :</b> '.$password.'
                                    </td>
                                </tr>
                                <tr>
                                    <td>En cas de soucis vous pouvez envoyer un email à <a href="mailto:contact@'.$this->site->host.'">contact@'.$this->site->host.'</a></td>
                                </tr>
                            </table>
                        </body>
                    ';
                    Mail::send('contact@'.$this->site->host, 'Contact', $user->email, $user->fullname, $this->site->label . 'Accès à '.$this->site->label , $contents);
		

                    $this->addFlash('Votre nouveau mot de passe vient de vous être envoyé par email', 'success');
                    $this->redirect($this->afterLoginPage);
                } else {
                    $errors['email'] = 'Cet addresse email ne correspond à aucun compte';
                }
            } else {
                $errors['email'] = 'Email obligatoire';
            }
        }

        $params = array(
            'imageLogin'    => $this->site->brand_logo->url,
            'pageTitle'     => 'Mot de passe oublié',
            'formAction'    => Router::url($this->forgotpasswordPage),
            'errors'        => $errors
        );
        $this->render('auth::forgotpassword', $params);
    }

    public function activateAction($email_verification_code)
    {
        $userClass = $this->loadModel('User');
        $user = $userClass::findBy('email_verification_code', $email_verification_code);
        if ($user !== null) {
            $user->active = 1;
            $user->email_verification_code = null;
            $user->email_verification_date = null;
            $user->save();
            $this->params['activated'] = true;
        } else {
            $this->params['activated'] = false;
        }
    }

    public function apiloginAction()
    {
        $params = array(
            'error' => false,
            'message' => '',
            'errors' => array()
        );
        $error= false;

        $post = $this->request->post;

        if (!empty($post) && isset($post[$this->idField]) && isset($post[$this->passwordField])) {
            $id = trim($post[$this->idField]);
            $password = trim($post[$this->passwordField]);

            if ($id == '') {
                $params['errors'][$this->idField] = 'Identifiant obligatoire';
            } else if (!filter_var($id, FILTER_VALIDATE_EMAIL)) {
                $params['errors'][$this->idField] = 'Email invalide';
            }

            if ($password == '') {
                $params['errors'][$this->passwordField] = 'Mot de passe obligatoire';
            }

            if (empty($params['errors'])) {
                $query = new Query();
                $query->select('*');
                $query->where($this->idField.' = :idField');
                $query->from($this->tableName);
                $res = $query->executeAndFetch(array('idField' => $id));

                if ($res && Password::check($password, $res->password)) {
                    $userClass = $this->loadModel('User');
                    $user = $userClass::findById($res->id);
                    if ($user->active == 1) {
                        $user->avatar = $user->media != null ? $user->media->getUrl() : null;
                        $this->session->set($this->sessionKey, $user);
                        $params['user'] = $user;
                    } else {
                        $error = true;
                    }
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
        } else {
            $error = true;
        }

        if ($error) {
            $params['error'] = true;
            $params['message'] = 'Identifiant ou mot de passe incorrect';
        }

        $this->render('', $params);
    }

    public function apilogoutAction()
    {
        $params = array(
            'error' => false,
            'message' => ''
        );

        $this->session->remove($this->sessionKey);
        $this->session->remove('site');
        $this->session->remove('fb_access_token');

        $params['message'] = 'Vous êtes maintenant déconnecté';

        $this->render('', $params);
    }

    public function apiisconnectedAction()
    {
        $user = $this->session->get($this->sessionKey);

        if ($user !== null) {
            $isConnected = true;
            $user->avatar = $user->media != null ? $user->media->getUrl() : null;
        } else {
            $isConnected = false;
        }

        $params = array(
            'error' => false,
            'message' => '',
            'isConnected' => $isConnected,
            'user' => $user
        );

        $this->render('', $params);
    }
}
