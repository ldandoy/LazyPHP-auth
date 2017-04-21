<?php

namespace Auth\controllers;

use app\controllers\FrontController;
use System\Router;
use System\Session;
use System\Password;

use Auth\models\User;

class UsersController extends FrontController
{
    /*
     * @var Auth\models\User
     */
    public $user = null;

    public function editAction($id)
    {
        if ($this->user === null) {
            $this->user = User::findById($id);
        }

        $this->render('edit', array(
            'id' => $id,
            'user' => $this->user,
            'formAction' => Router::url('users_update_'.$id)
        ));
    }

    public function updateAction($id)
    {
        $this->user = User::findById($id);
        $this->user->setData($this->request->post);

        if ($this->user->valid()) {
            $newPassword = isset($this->request->post['newPassword']) ? trim($this->request->post['newPassword']) : '';
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
                    $this->redirect('users_update_'.$id);
                } else {
                    Session::addFlash('Erreur mise à jour base de données', 'danger');
                }
            } else {
                Session::addFlash('Erreur(s) dans le formulaire', 'danger');
            }
        }

        $this->editAction($id);
    }
}
