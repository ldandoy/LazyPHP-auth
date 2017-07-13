<?php

namespace Auth\controllers;

use app\controllers\FrontController;
use Core\Router;
use Core\Session;
use Core\Password;

use Auth\models\User;

class UserController extends FrontController
{
    /*
     * @var Auth\models\User
     */
    public $user = null;

    public function indexAction()
    {
        if ($this->user === null) {
            $this->user = Session::get('current_user');
        }

        $this->params['user'] = $this->user;
        $this->params['title'] = $this->config['GENERAL']['title'];

        $this->render('auth::user::index', $this->params);
    }

    public function editAction()
    {
        if ($this->user === null) {
            $this->user = Session::get('current_user');
        }

        $this->render('auth::user::edit', array(
            'user' => $this->user,
            'formAction' => Router::url('user_update')
        ));
    }

    public function updateAction()
    {
        if ($this->user === null) {
            $this->user = Session::get('current_user');
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
                } else {
                    $this->user->errors['newPassword'] = 'Mot de passe invalide';
                }
            }

            if (empty($this->user->errors)) {
                if ($this->user->update((array)$this->user)) {
                    Session::addFlash('Votre compte a été modifié', 'success');
                    $this->redirect('/');
                } else {
                    Session::addFlash('Erreur mise à jour base de données', 'danger');
                }
            } else {
                Session::addFlash('Erreur(s) dans le formulaire', 'danger');
            }
        }

        $this->editAction();
    }
}
