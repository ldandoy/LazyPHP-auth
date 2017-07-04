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
        if (!empty($this->request->post) && isset($this->request->post['submit'])) {
            if ($this->save()) {
                Session::addFlash('Affectations des rôle modifées', 'success');
                $this->redirect('cockpit_auth_roles');
            } else {
                Session::addFlash('Erreur(s) dans le formulaire', 'danger');
            }
        }

        $roleAssignments = RoleAssignment::findAllSorted();
        $roles = Role::findAll();
        $groups = Group::findAll();
        $administrators = Administrator::findAll();
        $users = User::findAll();

        $this->render('auth::roleassignments::index', array(
            'roleAssignments' => $roleAssignments,
            'roles' => $roles,
            'groups' => $groups,
            'pageTitle' => '<i class="fa fa-picture-o fa-brown"></i> Gestion des rôles d\'utilisateurs',
            'boxTitle' => 'Affectations des rôles',
            'formAction' => Router::url('cockpit_auth_roleassignments')
        ));
    }

    private function save()
    {
        $post = $this->request->post;

        RoleAssignment::deleteAll();

        $roleAssignment = new RoleAssignment();

        foreach ($post as $k => $v) {
            if (strpos($k, 'group_') === 0) {
                $a = explode('_', $k);
                $roleAssignment->create(
                    array(
                        'group_id' => (int)$a[1],
                        'administrator_id' => null,
                        'user_id' => null,
                        'role_id' => (int)$a[3]
                    )
                );                
            // } else if () {
            // } else if () {
            }
        }

        return true;
    }
}
