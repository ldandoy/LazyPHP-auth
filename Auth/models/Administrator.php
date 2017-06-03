<?php

namespace Auth\models;

use Core\Model;
use Core\Query;
use Core\Password;

class Administrator extends Model
{
    protected $permittedColumns = array(
        'lastname',
        'firstname',
        'email',
        'password',
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
                'error' => 'Nom obligatoire'
            ),
            'firstname' => array(
                'type' => 'required',
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
     * Get an administrator by email
     *
     * @param string $email
     *
     * @return app\model\Administrator | bool
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
        return $this->firstname . " ".  $this->lastname;
    }
}
