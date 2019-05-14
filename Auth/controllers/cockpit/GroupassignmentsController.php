<?php

namespace Auth\controllers\cockpit;

use app\controllers\cockpit\CockpitController;
use Core\Session;
use Core\Router;

use Auth\models\Group;
use Auth\models\User;
use Auth\models\GroupAssignment;

class GroupassignmentsController extends CockpitController
{    
    public function indexAction()
    {
        if ($this->site !== null) {
            $where = 'site_id = '.$this->site->id;
        } else {
            $where = '';
        }

        if (!empty($this->request->post) && isset($this->request->post['submit'])) {
            if ($this->save()) {
                $this->addFlash('Affectations des rôle modifées', 'success');
                //$this->redirect('cockpit_auth_groupassignments');
            } else {
                $this->addFlash('Erreur(s) dans le formulaire', 'danger');
            }
        }

        $tabAssign = [];
        $groupAssignments = GroupAssignment::findAll();
        foreach ($groupAssignments as $assign) {
            $tabAssign[$assign->group_id][$assign->user_id] = 1;
        }
        
        $groups = Group::findAll('site_id is NULL OR site_id = ' . $this->site->id);
        $users = User::findAll($where);

        $this->render('auth::groupassignments::index', array(
            'groups'        => $groups,
            'users'         => $users,
            'tabAssign'     => $tabAssign,
            'pageTitle'     => '<i class="fa fa-picture-o fa-brown"></i> Gestion des groupes',
            'boxTitle'      => 'Affectations des groupes',
            'formAction'    => Router::url('cockpit_auth_groupassignments')
        ));
    }

    private function save()
    {
        $post = $this->request->post;

        # on récupère tous les ids
        $groupAssignments = GroupAssignment::findAll("site_id = " . $this->site->id . " AND group_id = ". $post['group_id']);

        # on supprime ceux qui ne sont pas dans la liste
        foreach ($groupAssignments as $v) {
            if (!array_key_exists("group_assignment_".$post['group_id']."_".$v->user_id, $post)) {
                $v->delete();
            }
        }

        # on ajoute les nouveaux
        foreach ($post as $k => $v) {
            if (strpos($k, 'group_assignment') === 0) {
                $a = explode('_', $k);
                $create = true;
                foreach ($groupAssignments as $v) {
                    if ($v->user_id == $a[3] && $v->group_id == $a[2]) {
                        $create = false;
                        break;
                    }
                }

                if ($create) {
                    $data = array(
                        'group_id'  => (int)$a[2],
                        'user_id'   => (int)$a[3],
                        'site_id'   => $this->site->id
                    );
                    $groupAssignment = new GroupAssignment();
                    $groupAssignment->create($data);
                }
            }
        }
        return true;
    }
}
