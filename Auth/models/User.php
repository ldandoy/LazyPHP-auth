<?php

namespace app\models;

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

    /**
     * Get an user by email
     *
     * @param string $email
     *
     * @return app\model\User | bool
     */
    public static function findByEmail($email)
    {
        $query = new Query();
        $query->select('*');
        $query->where('email = :email');
        $query->from(self::getTableName());

        return $query->executeAndFetch(array('email' => $email));
    }

    public function getValidations()
    {
        $validations = parent::getValidations();

        $validations = array_merge($validations, array(
            'lastname' => array(
                'type' => 'required',
                'filter' => 'trim',
                'error' => 'Nom obligatoire'
            ),
            'firstname' => array(
                'type' => 'required',
                'filter' => 'trim',
                'error' => 'Prénom obligatoire'
            ),
            'email' => array(
                array(
                    'type' => 'required',
                    'filter' => 'trim',
                    'error' => 'Email obligatoire'
                ),
                array(
                    'type' => 'email',
                    'filter' => 'trim',
                    'error' => 'Email invalide'
                )
            )
        ));

        return $validations;
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
