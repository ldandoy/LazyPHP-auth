<?php

namespace Auth\models;

use System\Model;
use System\Query;
use System\Password;

class User extends Model
{
    protected $permittedColumns = array(
        'lastname',
        'firstname',
        'email',
        'password',
        'address',
        'email_verification_code',
        'email_verification_date',
        'active'
    );

    public function getValidations()
    {
        $validations = parent::getValidations();

        $validations = array_merge($validations, array(
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
                    'filters' => 'trim',
                    'error' => 'Email invalide'
                )
            )
        ));

        return $validations;
    }

    /**
     * Get an user by email
     *
     * @param string $email
     *
     * @return Auth\models\User | bool
     */
    public static function findByEmail($email)
    {
        $query = new Query();
        $query->select('*');
        $query->where('email = :email');
        $query->from(self::getTableName());

        return $query->executeAndFetch(array('email' => $email));
    }

    /**
     * Get fullname : <lastname>[ <firstname>]
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->lastname.ltrim(' '.$this->firstname);
    }

    /**
     * Get user list for options in a select input
     */
    public static function getOptions()
    {
        $options = array();

        $users = self::findAll();

        foreach ($users as $user) {
            $options[$user->id] = array(
                'value' => $user->id,
                'label' => $user->getFullName()
            );
        }

        return $options;
    }
}
