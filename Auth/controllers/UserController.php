<?php

namespace Auth\controllers;

use app\controllers\FrontController;
use Core\Router;
use Core\Session;
use Core\Password;
use Core\Mail;

use Auth\models\User;

class UserController extends FrontController
{
    /**
     * @var Auth\models\User
     */
    private $user = null;

    public function indexAction()
    {
        if ($this->user === null) {
            $this->user = $this->session->get('current_user');
        }

        $orderClass = $this->loadModel('Order'); 
        if ($this->site !== null ) {
            $where = 'site_id = '.$this->site->id;

        } else {
            $where = '';
        }
        $where .=  ' and user_id=' . $this->user->id;

        $orders = $orderClass::findAll($where );

        

        $this->params['user'] = $this->user;
        $this->params['title'] = $this->config['GENERAL']['title'];
        $this->params['orders'] = $orders;


        $this->render(
            'user::index',
            $this->params
        );

    }

    public function editAction()
    {
        if ($this->user === null) {
            $this->user = $this->session->get('current_user');
        }

        $this->render(
            'user::edit',
            array(
                'user' => $this->user,
                'formAction' => Router::url('user_update')
            )
        );
    }

    public function updateAction()
    {
        if ($this->user === null) {
            $this->user = $this->session->get('current_user');
        }

        $post = $this->request->post;

        if (isset($post['media_id']) && $post['media_id'] == '') {
            $post['media_id'] = null;
        }
        $this->user->setData($post);

        if ($this->user->valid()) {
            $newPassword = isset($post['newPassword']) ? trim($post['newPassword']) : '';
            if ($newPassword != '') {
                if (Password::validPassword($newPassword)) {
                    $this->user->password = Password::crypt($newPassword);
                    $contents=  '
                            <body>
                                <table>
                                    <tr>
                                        <td><h1>Voici vos accès au Bureau Virtuel</h1></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>URL:</b> <a href="https://'.$this->site->host.'" target="_blank">https://'.$this->site->host.'</a><br />
                                            <b>Login:</b> '.$this->user->email.'<br />
                                            <b>Mot de passe :</b> '.$this->user->password.'
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>En cas de soucis vous pouvez envoyer un email à <a href="mailto:contact@'.$this->site->host.'">contact@'.$this->site->host.'</a></td>
                                    </tr>
                                </table>
                            </body>
                        ';
                        Mail::send('contact@'.$this->site->host, 'Contact', $this->user->email, $this->user->fullname, "[".$this->site->label . '] Rappel de vos identifiant' , $contents);
                } else {
                    $this->user->errors['newPassword'] = 'Mot de passe invalide';
                }
            }

            if (empty($this->user->errors)) {
                if ($this->user->update((array)$this->user)) {
                    $this->addFlash('Votre compte a été modifié', 'success');
                    $this->redirect('/user');
                } else {
                    $this->addFlash('Erreur mise à jour base de données', 'danger');
                }
            } else {
                $this->addFlash('Erreur(s) dans le formulaire', 'danger');
            }
        }

        $this->editAction();
    }
}
