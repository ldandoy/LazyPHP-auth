<?php

namespace Auth\controllers\cockpit;

use app\controllers\cockpit\CockpitController;
use Core\Router;
use Core\Session;
use Core\Password;

use Auth\models\Administrator;
use Auth\models\Group;

class AdministratorsController extends CockpitController
{
    /*
     * @var Auth\models\Administrator
     */
    public $administrator = null;

    public function indexAction()
    {
        $administrators = Administrator::findAll();

        $this->render('auth::administrators::index', array(
            'administrators' => $administrators,
            'pageTitle' => '<i class="fa fa-user-secret"></i> Administrateurs'
        ));
    }

    public function showAction()
    {
        $this->render('auth::administrators::show', array(
            'administrator' => $this->current_administrator,
            'pageTitle' => '<i class="fa fa-user-secret"></i> Administrateurs'
        ));
    }

    public function newAction()
    {
        if ($this->administrator === null) {
            $this->administrator = new Administrator();
        }

        $groupOptions = Group::getOptions();

        $this->render('auth::administrators::edit', array(
            'id' => 0,
            'administrator' => $this->administrator,
            'pageTitle' => 'Nouvel administrateur',
            'groupOptions' => $groupOptions,
            'formAction' => Router::url('cockpit_auth_administrators_create')
        ));
    }

    public function editAction($id)
    {
        if ($this->administrator === null) {
            $this->administrator = Administrator::findById($id);
        }

        $groupOptions = Group::getOptions();

        $this->render('auth::administrators::edit', array(
            'id' => $id,
            'administrator' => $this->administrator,
            'pageTitle' => 'Modification administrateur n°'.$id,
            'groupOptions' => $groupOptions,
            'formAction' => Router::url('cockpit_auth_administrators_update_'.$id)
        ));
    }

    public function createAction()
    {
        $this->administrator = new Administrator();
        $this->administrator->setData($this->request->post);

        if ($this->administrator->valid()) {
            $password = Password::generatePassword();
            $cryptedPassword = Password::crypt($password);
            $this->administrator->password = $cryptedPassword;

            $this->administrator->email_verification_code = Password::generateToken();
            $this->administrator->email_verification_date = date('Y-m-d H:i:s');
            $this->administrator->active = 0;

            if ($this->administrator->create((array)$this->administrator)) {
                Session::addFlash('Administrateur ajouté', 'success');
                $this->redirect('cockpit_auth_administrators');
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
        $this->administrator = Administrator::findById($id);
        $this->administrator->setData($this->request->post);

        if ($this->administrator->valid()) {
            $newPassword = trim($this->request->post['newPassword']);
            if ($newPassword != '') {
                if (Password::validPassword($newPassword)) {
                    $this->administrator->password = Password::crypt($newPassword);
                } else {
                    $this->administrator->errors['newPassword'] = 'Mot de passe invalide';
                }
            }

            if (empty($this->administrator->errors)) {
                if ($this->administrator->update((array)$this->administrator)) {
                    Session::addFlash('Administrateur modifié', 'success');
                    $this->redirect('cockpit_auth_administrators');
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
        $administrator = Administrator::findById($id);
        $administrator->delete();
        Session::addFlash('Administrateur supprimé', 'success');
        $this->redirect('cockpit_auth_administrators');
    }
}
