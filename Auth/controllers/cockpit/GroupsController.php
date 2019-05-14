<?php

namespace Auth\controllers\cockpit;

use app\controllers\cockpit\CockpitController;
use Core\Session;
use Core\Router;

use Auth\models\Group;

class GroupsController extends CockpitController
{
    /**
     * @var Auth\models\Group
     */
    private $group = null;

    /**
     * @var string
     */
    private $pageTitle = '<i class="fa fa-picture-o fa-brown"></i> Gestion des groupes d\'utilisateurs';

    public function indexAction()
    {
        $groups = Group::findAll('site_id is NULL OR site_id = ' . $this->site->id);

        $this->render(
            'auth::groups::index',
            array(
                'groups' => $groups,
                'pageTitle' => $this->pageTitle,
                'boxTitle' => 'Liste des groupes d\'utilisateurs'
            )
        );
    }

    public function newAction()
    {
        if ($this->group === null) {
            $this->group = new Group();
        }

        $this->render(
            'auth::groups::edit',
            array(
                'group' => $this->group,
                'pageTitle' => $this->pageTitle,
                'boxTitle' => 'Nouveau groupe',
                'formAction' => Router::url('cockpit_auth_groups_create')
            )
        );
    }

    public function editAction($id)
    {
        if ($this->group === null) {
            $this->group = Group::findById($id);
        }

        $this->render(
            'auth::groups::edit',
            array(
                'group' => $this->group,
                'pageTitle' => $this->pageTitle,
                'boxTitle' => 'Modification group n°'.$id,
                'formAction' => Router::url('cockpit_auth_groups_update_'.$id)
            )
        );
    }

    public function createAction()
    {
        $this->group = new Group();

        if (!isset($this->request->post['cockpit'])) {
            $this->request->post['cockpit'] = 0;
        }

        if ($this->group->save($this->request->post)) {
            $this->addFlash('Groupe ajouté', 'success');
            $this->redirect('cockpit_auth_groups');
        } else {
            $this->addFlash('Erreur(s) dans le formulaire', 'danger');
        }

        $this->newAction();
    }

    public function updateAction($id)
    {
        $this->group = Group::findById($id);

        if (!isset($this->request->post['cockpit'])) {
            $this->request->post['cockpit'] = 0;
        }

        if ($this->group->save($this->request->post)) {
            $this->addFlash('Groupe modifié', 'success');
            $this->redirect('cockpit_auth_groups');
        } else {
            $this->addFlash('Erreur(s) dans le formulaire', 'danger');
        }

        $this->editAction($id);
    }

    public function deleteAction($id)
    {
        $group = Group::findById($id);
        $group->delete();
        $this->addFlash('Groupe supprimé', 'success');
        $this->redirect('cockpit_auth_groups');
    }
}
