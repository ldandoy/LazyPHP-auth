<?php

namespace app\controllers\cockpit;

use app\controllers\cockpit\CockpitController;
use app\models\User;

use System\Router;
use System\Session;
use System\Password;

class UsersController extends CockpitController
{
    /*
     * @var app\models\User
     */
    public $user = null;

    public function indexAction()
    {
        $this->users = User::findAll();

        $this->render('index', array(
            'pageTitle' => '<i class="fa fa-users"></i> Utilisateurs',
            'users' => $this->users
        ));
    }

    public function newAction()
    {
        if ($this->user === null) {
            $this->user = new User();
        }

        $this->render('edit', array(
            'id' => 0,
            'user' => $this->user,
            'pageTitle' => 'Nouvel utilisateur',
            'formAction' => Router::url('cockpit_users_create')
        ));
    }

    public function editAction($id)
    {
        if ($this->user === null) {
            $this->user = User::findById($id);
        }

        $this->render('edit', array(
            'id' => $id,
            'user' => $this->user,
            'pageTitle' => 'Modification utilisateur n°'.$id,
            'formAction' => Router::url('cockpit_users_update_'.$id)
        ));
    }

    public function createAction()
    {
        $this->user = new User();
        $this->user->setData($this->request->post);

        if ($this->user->valid()) {
            $password = Password::generatePassword();
            $cryptedPassword = Password::crypt($password);
            $this->user->password = $cryptedPassword;

            $this->user->email_verification_code = Password::generateToken();
            $this->user->email_verification_date = date('Y-m-d H:i:s');
            $this->user->active = 0;

            if ($this->user->create((array)$this->user)) {
                Session::addFlash('Utilisateur ajouté', 'success');
                $this->redirect('cockpit_users');
            } else {
                Session::addFlash('Erreur insertion base de données', 'danger');
            };
        } else {
            Session::addFlash('Erreur(s) dans le formulaire', 'danger');
        }

        $this->newAction();
    }

    public function updateAction($id)
    {
        $this->user = User::findById($id);
        $this->user->setData($this->request->post);

        if ($this->user->valid()) {
            $newPassword = trim($this->request->post['newPassword']);
            if ($newPassword != '') {
                if (Password::validPassword($newPassword)) {
                    $this->user->password = Password::crypt($newPassword);
                } else {
                    $this->user->errors['newPassword'] = 'Mot de passe invalide';
                }
            }

            if (empty($this->user->errors)) {
                if ($this->user->update((array)$this->user)) {
                    Session::addFlash('Utilisateur modifié', 'success');
                    $this->redirect('cockpit_users');
                } else {
                    Session::addFlash('Erreur mise à jour base de données', 'danger');
                }
            } else {
                Session::addFlash('Erreur(s) dans le formulaire', 'danger');
            }
        }

        $this->editAction($id);
    }

    public function deleteAction($id)
    {
        $user = User::findById($id);
        $user->delete();
        $this->Session->setFlash('Utilisateur supprimé', 'success');
        $this->redirect('cockpit_users');
    }
}
