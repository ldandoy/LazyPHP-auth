<?php

namespace Auth\controllers\cockpit;

use app\controllers\cockpit\CockpitController;
use Core\Router;
use Core\Password;
use Core\AttachedFile;

use Auth\models\User;
use Auth\models\Group;
use Core\models\Site;

class UsersController extends CockpitController
{
    /**
     * @var Auth\models\User
     */
    private $user = null;

    /**
     * @var string
     */
    private $pageTitle = '<i class="fa fa-users"></i> Gestion des utilisateurs';

    public function indexAction()
    {
        if ($this->site !== null) {
            $where = 'site_id = '.$this->site->id;
        } else {
            $where = '';
        }
        $users = User::findAll($where);

        $this->render(
            'auth::users::index',
            array(
                'pageTitle' => $this->pageTitle,
                'users' => $users
            )
        );
    }

    public function newAction()
    {
        if ($this->user === null) {
            $this->user = new User();
        }

        $groupOptions = Group::getOptions();
        $siteOptions = Site::getOptions();

        $this->render(
            'auth::users::edit',
            array(
                'id' => 0,
                'user' => $this->user,
                'pageTitle' => $this->pageTitle,
                'boxTitle' => 'Nouvel utilisateur',
                'groupOptions' => $groupOptions,
                'siteOptions' => $siteOptions,
                'selectSite' => $this->current_user->site_id === null,
                'formAction' => Router::url('cockpit_auth_users_create')
            )
        );
    }

    public function editAction($id)
    {
        if ($this->user === null) {
            $this->user = User::findById($id);
        }

        $groupOptions = Group::getOptions();
        $siteOptions = Site::getOptions();

        $this->render(
            'auth::users::edit',
            array(
                'id' => $id,
                'user' => $this->user,
                'pageTitle' => $this->pageTitle,
                'boxTitle' => 'Modification utilisateur n°'.$id,
                'groupOptions' => $groupOptions,
                'siteOptions' => $siteOptions,
                'selectSite' => $this->current_user->site_id === null,
                'formAction' => Router::url('cockpit_auth_users_update_'.$id)
            )
        );
    }

    public function createAction()
    {
        if (!isset($this->request->post['site_id'])) {
            $this->request->post['site_id'] = $this->site->id;
        }

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
                $this->addFlash('Utilisateur ajouté', 'success');
                $this->redirect('cockpit_auth_users');
            } else {
                $this->addFlash('Erreur insertion base de données', 'danger');
            }
        } else {
            $this->addFlash('Erreur(s) dans le formulaire', 'danger');
        }

        $this->newAction();
    }

    public function updateAction($id)
    {
        if (!isset($this->request->post['site_id'])) {
            $this->request->post['site_id'] = $this->site->id;
        }

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
                    $this->addFlash('Utilisateur modifié', 'success');
                    $this->redirect('cockpit_auth_users');
                } else {
                    $this->addFlash('Erreur mise à jour base de données', 'danger');
                }
            } else {
                $this->addFlash('Erreur(s) dans le formulaire', 'danger');
            }
        }

        $this->editAction($id);
    }

    public function deleteAction($id)
    {
        $user = User::findById($id);
        $user->delete();
        $this->addFlash('Utilisateur supprimé', 'success');
        $this->redirect('cockpit_auth_users');
    }

    public function importcsvAction()
    {
        $errors = array();

        $post = $this->request->post;

        if (isset($post['submit']) && $post['submit'] == 'importcsv') {
            if (!isset($post['site_id'])) {
                $post['site_id'] = $this->site->id;
            }

            if (!isset($post['file'])) {
                $errors['file'] = 'Fichier obligatoire';
            } else {
                $af = new AttachedFile(null, $post['file'][0], '.csv');
                $errorFile = $af->valid();

                if ($errorFile !== true) {
                    $errors['file'] = 'Erreur fichier : '.$errorFile;
                }
            }

            if (empty($errors)) {
                $path = $af->uploadedFile['tmp_name'];                

                $f = fopen($path, 'r');
                $r = 0;
                while (($row = fgetcsv($f, 0, ';', '"', '\\')) !== false) {
                    // header
                    if ($r == 0/*$row[0] == 'lastname' && $row[1] == 'firstname'*/) {
                        $r++;
                        continue;
                    }

                    $user = new User();

                    $user->site_id = $post['site_id'];

                    $user->lastname = $row[0];
                    $user->firstname = $row[1];
                    $user->email = $row[2];

                    $password = $row[3] != '' ? $row[3] : Password::generatePassword(); 
                    $cryptedPassword = Password::crypt($password);
                    $user->password = $cryptedPassword;

                    $user->email_verification_code = Password::generateToken();
                    $user->email_verification_date = date('Y-m-d H:i:s');
                    $user->active = 1;

                    // echo $row[0].' '.$row[1];
                    // if ($user->save()) {
                    //     echo ' => OK';
                    // } else {
                    //     echo ' => * erreur *';
                    // }
                    // echo '<br />';

                    $r++;
                }

                $this->addFlash('Erreur(s) dans le formulaire', 'danger');
                $this->redirect('cockpit_auth_users');
            } else {
                $this->addFlash('Erreur(s) dans le formulaire', 'danger');
            }
        }

        $siteOptions = Site::getOptions();

        $this->render(
            'auth::users::importcsv',
            array(
                'pageTitle' => $this->pageTitle,
                'boxTitle' => 'Importer des utilisateurs',
                'siteOptions' => $siteOptions,
                'selectSite' => $this->current_user->site_id === null,
                'formAction' => Router::url('cockpit_auth_users_importcsv'),
                'errors' => $errors
            )
        );
    }
}
