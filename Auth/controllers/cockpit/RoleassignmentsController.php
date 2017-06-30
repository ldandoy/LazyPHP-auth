<?php

namespace Auth\controllers\cockpit;

use app\controllers\cockpit\CockpitController;
use Core\Session;
use Core\Router;

use Auth\models\Group;
use Auth\models\Administrator;
use Auth\models\User;
use Auth\models\Role;
use Auth\models\RoleAssignment;

class RoleassignmentsController extends CockpitController
{
    public function indexAction()
    {
        $roles = Role::findAll();
        $roleAssignments = RoleAssignment::findAll();


        $allGroups = Group::findAll();
        $groups = array();
        foreach ($roles as $role) {
            foreach ($roleAssignments as $roleAssignment) {
            }
        }
        $administrators = Administrator::findAll();
        $users = User::findAll();

        $this->render('auth::roleassignments::index', array(
            'groups' => $groups,
            'administrators' => $administrators,
            'users' => $users,
            'roles' => $roles,
            'roleAssignments' => $roleAssignments,
            'pageTitle' => '<i class="fa fa-picture-o fa-brown"></i> Gestion des rôles d\'utilisateurs',
            'boxTitle' => 'Liste des roles d\'utilisateurs'
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
            Session::addFlash('Rôle ajouté', 'success');
            $this->redirect('cockpit_auth_roles');
        } else {
            Session::addFlash('Erreur(s) dans le formulaire', 'danger');
        }

        $this->newAction();
    }

    public function updateAction($id)
    {
        $this->role = Role::findById($id);

        if ($this->role->save($this->request->post)) {
            Session::addFlash('Rôle modifié', 'success');
            $this->redirect('cockpit_auth_roles');
        } else {
            Session::addFlash('Erreur(s) dans le formulaire', 'danger');
        }

        $this->editAction($id);
    }

    public function deleteAction($id)
    {
        $role = Role::findById($id);
        $role->delete();
        Session::addFlash('Rôle supprimé', 'success');
        $this->redirect('cockpit_auth_roles');
    }
}
