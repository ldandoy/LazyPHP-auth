<?php

namespace Auth\controllers\cockpit;

use app\controllers\cockpit\CockpitController;
use Core\Router;
use Core\Password;
use Core\AttachedFile;
use Core\Mail;

use Auth\models\User;
use Auth\models\Group;
use Core\models\Site;
use Auth\models\GroupAssignment;

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
        $userClass = $this->loadModel('User');
        $users = $userClass::findAll($where);

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
        $userClass = $this->loadModel('User');
        if ($this->user === null) {
            $this->user = new $userClass();
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
            $userClass = $this->loadModel('User');
            $this->user = $userClass::findById($id);
        }

        $groupOptions = Group::getOptions();
        $siteOptions = Site::getOptions();

        $groupsOptions = [];
        foreach ($this->user->getGroups() as $value) {
            $groupsOptions[] = $value->id;
        }

        $this->render(
            'auth::users::edit',
            array(
                'id'            => $id,
                'user'          => $this->user,
                'pageTitle'     => $this->pageTitle,
                'boxTitle'      => 'Modification utilisateur n°'.$id,
                'groupOptions'  => $groupOptions,
                'siteOptions'   => $siteOptions,
                'selectSite'    => $this->current_user->site_id === null,
                'formAction'    => Router::url('cockpit_auth_users_update_'.$id),
                'groups'        => $groupsOptions
            )
        );
    }

    public function createAction()
    {
        if (!isset($this->request->post['site_id'])) {
            $this->request->post['site_id'] = $this->site->id;
        }

        $userClass = $this->loadModel('User');
        $this->user = new $userClass();
        $this->user->setData($this->request->post);

        if ($this->user->valid()) {
            $password = Password::generatePassword();
            $cryptedPassword = Password::crypt($password);
            $this->user->password = $cryptedPassword;

            $this->user->email_verification_code = Password::generateToken();
            $this->user->email_verification_date = date('Y-m-d H:i:s');
            $this->user->active = 1;

            if ($this->user->create((array)$this->user)) {

                $groups = $this->request->post['groups'];

                foreach ($groups as $value) {
                    $groupClass = $this->loadModel('Group');
                    $group = $groupClass::findById($value);
        
                    $data = array(
                        'group_id' => $group->id,
                        'user_id' => $this->user->lastInsertId,
                    );
                    $groupAssignment = new GroupAssignment();
                    $groupAssignment->create($data);
                }

                $this->addFlash('Utilisateur ajouté', 'success');
                if ($this->site->sender_mail != null || $this->site->sender_mail != "") {
                    $sender = $this->site->sender_mail;
                } else {
                    $sender = 'contact@'.$this->site->host;
                }
		        $contents=  '
                    <body>
                        <table>
                            <tr>
                                <td><h1>Voici vos accès au site du '.$this->site->label . '</h1></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>URL:</b> <a href="https://'.$this->site->host.'" target="_blank">https://'.$this->site->host.'</a><br />
                                    <b>Login:</b> '.$this->user->email.'<br />
                                    <b>Mot de passe :</b> '.$password.'
                                </td>
                            </tr>
                            <tr>
                                <td>En cas de soucis vous pouvez envoyer un email à <a href="mailto:'.$sender.'">'.$sender.'</a></td>
                            </tr>
                        </table>
                    </body>
                ';
                Mail::send($sender, 'Contact', $this->user->email, $this->user->fullname, '['.$this->site->label . '] Création de votre compte' , $contents);
		
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

        $userClass = $this->loadModel('User');
        $this->user = $userClass::findById($id);
        $this->user->setData($this->request->post);
        $groups = $this->request->post['groups'];
        $userGroups = $this->user->getGroups();

        // On gère la suppression des groupes ici.
        foreach ($userGroups as $value) {
            if (!in_array($value->id, $groups)) {
                $groupAssignment = new GroupAssignment();
                $where = "group_id = " . $value->id . " AND user_id = " . $this->user->id;
                $groupAssignment = $groupAssignment::findOne($where);
                $groupAssignment->delete();
            }
        }

        // On ajoute les groupes ici
        foreach ($groups as $value) {
            $groupClass = $this->loadModel('Group');
            $group = $groupClass::findById($value);

            if (!in_array($group, $userGroups)) {
                $data = array(
                    'group_id' => $group->id,
                    'user_id' => $this->user->id,
                    'site_id' => $this->site->id
                );
                $groupAssignment = new GroupAssignment();
                $groupAssignment->create($data);
            }
        }

        if(!isset($this->request->post['active'])) {
            $this->user->active = 0;
        }

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
        $userClass = $this->loadModel('User');
        $user = $userClass::findById($id);
        $user->delete();
        $this->addFlash('Utilisateur supprimé', 'success');
        $this->redirect('cockpit_auth_users');
    }

    public function sendnewpasswordAction($user_id) {
        $userClass = $this->loadModel('User');
        $this->user = $userClass::findById($user_id);

        $password = Password::generatePassword();
        $cryptedPassword = Password::crypt($password);
        $this->user->password = $cryptedPassword;
        $this->user->save(); 

        $this->addFlash('Le mot de passe a bien été renvoyé', 'success');
        if ($this->site->sender_mail != null || $this->site->sender_mail != "") {
            $sender = $this->site->sender_mail;
        } else {
            $sender = 'contact@'.$this->site->host;
        }

        $contents=  '
            <body>
                <table>
                    <tr>
                        <td><h1>Voici vos accès au site du '.$this->site->label . '</h1></td>
                    </tr>
                    <tr>
                        <td>
                            <b>URL:</b> <a href="https://'.$this->site->host.'" target="_blank">https://'.$this->site->host.'</a><br />
                            <b>Login:</b> '.$this->user->email.'<br />
                            <b>Mot de passe :</b> '.$password.'
                        </td>
                    </tr>
                    <tr>
                        <td>En cas de soucis vous pouvez envoyer un email à <a href="mailto:'.$sender.'">'.$sender.'</a></td>
                    </tr>
                </table>
            </body>
        ';
        Mail::send($sender, 'Contact', $this->user->email, $this->user->fullname, "[".$this->site->label . '] Renvoi de vos accès', $contents);

        $this->redirect('cockpit_auth_users');
    }

    private function slugify($string, $delimiter = '-') {
        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        setlocale(LC_ALL, $oldLocale);
        return $clean;
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
                $groupClass = $this->loadModel('Group');
                $groupUser = $groupClass::findBy('code', 'users');

                $path = $af->uploadedFile['tmp_name'];                

                // On gère les groupes
                $groupClass = $this->loadModel('Group');
                $groups = $groupClass::findAll("site_id = " . $this->site->id . " OR site_id is null");

                foreach($groups as $group) {
                    $groupsName[] = $group->label;
                }
                $f = fopen($path, 'r');
                $r = 0;
                while (($row = fgetcsv($f, 0, ';', '"', '\\')) !== false) {
                    // header
                    if ($r == 0/*$row[0] == 'lastname' && $row[1] == 'firstname'*/) {
                        $r++;
                        continue;
                    }

                    if (!in_array($row[4], $groupsName)) {
                        $group = new $groupClass();
                        $group->label = $row[4];
                        $group->code = $this->slugify($row[4]);
                        $group->cockpit = 0;
                        $group->site_id = $this->site->id;
                        $group->save();
                        $groups[] = $group;
                        $groupsName[] = $group->label;
                    }
                }

                // On gère les users
                $f = fopen($path, 'r');
                $r = 0;
                while (($row = fgetcsv($f, 0, ';', '"', '\\')) !== false) {
                    // header
                    if ($r == 0/*$row[0] == 'lastname' && $row[1] == 'firstname'*/) {
                        $r++;
                        continue;
                    }
                    $userClass = $this->loadModel('User');
                    $user = new $userClass();
                    
                    $user->site_id = $post['site_id'];

                    $user->lastname = $row[0];
                    $user->firstname = $row[1];
                    $user->email = $row[2];
                    $user->inside_at = date("Y-m-d H:i:s", strtotime($row[4]));
                    
                    $password = $row[3] != '' ? $row[3] : Password::generatePassword(); 
                    $cryptedPassword = Password::crypt($password);
                    $user->password = $cryptedPassword;

                    $user->group_id = $groupUser->id;

                    $user->email_verification_code = Password::generateToken();
                    $user->email_verification_date = date('Y-m-d H:i:s');
                    $user->active = 1;

                    if ($user->save()) {
                        $groupAssignmentClass = $this->loadModel('GroupAssignment');
                        $groupAssignment = new $groupAssignmentClass();
                        $groupAssignment->user_id = $user->id;
                        foreach($groups as $group) {
                            if ($row[4] == $group->label) {
                                $groupAssignment->group_id = $group->id;
                            }
                        }
                        $groupAssignment->save();

                        if ($this->site->sender_mail != null || $this->site->sender_mail != "") {
                            $sender = $this->site->sender_mail;
                        } else {
                            $sender = 'contact@'.$this->site->host;
                        }
                        $contents=  '
                            <body>
                                <table>
                                    <tr>
                                        <td><h1>Voici vos accès au site du '.$this->site->label . '</h1></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>URL:</b> <a href="https://'.$this->site->host.'" target="_blank">https://'.$this->site->host.'</a><br />
                                            <b>Login:</b> '.$user->email.'<br />
                                            <b>Mot de passe :</b> '.$password.'
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>En cas de soucis vous pouvez envoyer un email à <a href="mailto:'.$sender.'">'.$sender.'</a></td>
                                    </tr>
                                </table>
                            </body>
                        ';
                        Mail::send($sender, 'Contact', $user->email, $user->fullname, "[".$this->site->label . '] Création de votre compte' , $contents);
                    } else {
                        $this->addFlash("Erreur(s) lors de la création d'utilisateur: " . $r, 'danger');
                    }
                    $r++;
                }
                $this->addFlash('Fichier bien importé', 'success');
                $this->redirect('cockpit_auth_users');
            } else {
                $this->addFlash('Erreur(s) dans le formulaire', 'danger');
            }
        }

        $siteClass = $this->loadModel('Site');
        $siteOptions = $siteClass::getOptions();

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

    public function exportcsvAction()
    {
        $userClass = $this->loadModel('User');
        $users = $userClass::findAll("site_id = " . $this->site->id);
        
        $csv = "Nom;Prénom;Email;Poste;Actif;Type;Groupes\n";
        foreach ($users as $user) {
            $csv .= $user->lastname . ';';
            $csv .= $user->firstname . ';';
            $csv .= $user->email . ';';
            $csv .= $user->poste . ';';
            $csv .= $user->active . ';';
            $csv .= $user->group->label . ';';
            
            // Get groups
            $classAssignGroup = $this->loadModel('GroupAssignment');
            $groupsAssigns = $classAssignGroup::findByUser($user->id);
            foreach ($groupsAssigns as $groupsAssig) {
                $csv .= $groupsAssig->group->label. ", ";
            }
            $csv .= "\n";
        }

        $this->render(
            '',
            array(
                'filename' => 'listeuser'.date('dmY').'.csv',
                'csv' => $csv
            ),
            false
        );
    }
}
