<?php

namespace Auth\controllers\cockpit;

use app\controllers\cockpit\CockpitController;
use Core\Session;
use Core\Router;

use Auth\models\Role;

class RolesController extends CockpitController
{
    /*
     * @var Auth\models\Role
     */
    public $role = null;

    public function indexAction()
    {
        $roles = Role::findAll();

        $this->render('auth::roles::index', array(
            'roles' => $roles,
            'pageTitle' => '<i class="fa fa-picture-o fa-brown"></i> Gestion des rôles d\'utilisateurs',
            'boxTitle' => 'Liste des rôles d\'utilisateurs'
        ));
    }

    public function newAction()
    {
        if ($this->role === null) {
            $this->role = new Role();
        }

        $this->render('auth::roles::edit', array(
            'role' => $this->role,
            'pageTitle' => '<i class="fa fa-picture-o fa-brown"></i> Gestion des rôles d\'utilisateurs',
            'boxTitle' => 'Nouveau rôle',
            'formAction' => Router::url('cockpit_auth_roles_create')
        ));
    }

    public function editAction($id)
    {
        if ($this->role === null) {
            $this->role = Role::findById($id);
        }

        $this->render('auth::roles::edit', array(
            'role' => $this->role,
            'pageTitle' => '<i class="fa fa-picture-o fa-brown"></i> Gestion des rôles d\'utilisateurs',
            'boxTitle' => 'Modification rôle n°'.$id,
            'formAction' => Router::url('cockpit_auth_roles_update_'.$id)
        ));
    }

    public function createAction()
    {
        $this->role = new Role();

        if ($this->role->save($this->request->post)) {
            $this->addFlash('Rôle ajouté', 'success');
            $this->redirect('cockpit_auth_roles');
        } else {
            $this->addFlash('Erreur(s) dans le formulaire', 'danger');
        }

        $this->newAction();
    }

    public function updateAction($id)
    {
        $this->role = Role::findById($id);

        if ($this->role->save($this->request->post)) {
            $this->addFlash('Rôle modifié', 'success');
            $this->redirect('cockpit_auth_roles');
        } else {
            $this->addFlash('Erreur(s) dans le formulaire', 'danger');
        }

        $this->editAction($id);
    }

    public function deleteAction($id)
    {
        $role = Role::findById($id);
        $role->delete();
        $this->addFlash('Rôle supprimé', 'success');
        $this->redirect('cockpit_auth_roles');
    }
}
