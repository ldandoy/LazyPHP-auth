<?php

namespace Auth\models;

use Core\Model;
use Core\Query;
use Core\Password;
use Auth\models\RoleAssignment;

class User extends Model
{
    protected $permittedColumns = array(
        'site_id',
        'lastname',
        'firstname',
        'email',
        'phone',
        'password',
        'email_verification_code',
        'email_verification_date',
        'group_id',
        'media_id',
        'active',
    );

    public function __construct($data = array())
    {
        parent::__construct($data);

        $this->fullname = $this->getFullName();
    }

    /**
     * Get list of associed table(s)
     *
     * @return mixed
     */
    public function getAssociations()
    {
        $associations = parent::getAssociations();

        return array_merge(
            $associations,
            array(
                'site' => array(
                    'type' => '1',
                    'model' => 'Core\\models\\Site',
                    'key' => 'site_id'
                ),
                'media' => array(
                    'type' => '1',
                    'model' => 'Media\\models\\Media',
                    'key' => 'media_id'
                ),
                'group' => array(
                    'type' => '1',
                    'model' => 'Auth\\models\\Group',
                    'key' => 'group_id'
                ),
                'order' => array(
                'type' => '1',
                'model' => 'Order',
                'key' => 'order_id'
            ),
            )
        );
    }

    public function getValidations()
    {
        $validations = parent::getValidations();

        $validations = array_merge($validations, array(
            'site_id' => array(
                'type' => 'required',
                'defaultValue' => null
            ),
            'lastname' => array(
                'type' => 'required',
                'filters' => 'trim',
                'error' => 'Nom obligatoire'
            ),
            'firstname' => array(
                'type' => 'required',
                'filters' => 'trim',
                'error' => 'Prénom obligatoire'
            ),
            'email' => array(
                array(
                    'type' => 'required',
                    'filters' => 'trim',
                    'error' => 'Email obligatoire'
                ),
                array(
                    'type' => 'email',
                    'error' => 'Email invalide'
                )
            )
        ));

        return $validations;
    }

    public function getOptionLabel()
    {
        return '['.$this->id.'] '.$this->getFullName();
    }

    /**
     * Get an user by email
     *
     * @param string $email
     *
     * @return Auth\models\User
     */
    public static function findByEmail($email)
    {
        $res = self::findAll('email = \''.$email.'\'');
        if (!empty($res)) {
            return $res[0];
        } else {
            return null;
        }
    }

    /**
     * Get fullname : <lastname>[ <firstname>]
     *
     * @return string
     */
    public function getFullName()
    {
        return ltrim($this->firstname.' ').$this->lastname;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImageUrl()
    {
        if ($this->media !== null) {
        return $this->media->getUrl();
        } else {
            return '';
        }
    }

    public static function checkPermission($user, $roleCode)
    {
        if ($user !== null && $user->group_id !== null) {
            $roleAssignments = array_merge(
                RoleAssignment::findByGroup($user->group_id),
                RoleAssignment::findByUser($user->id)
            );

            if (!empty($roleAssignments)) {
                foreach ($roleAssignments as $roleAssignment) {
                    if ($roleAssignment->role != null && $roleAssignment->role->code == $roleCode) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

}
