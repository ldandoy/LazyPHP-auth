<?php

namespace app\models;

use System\Model;
use System\Query;
use System\Password;

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
                'type' => 'required',
                'error' => 'Email obligatoire'
            ),
            'email' => array(
                'type' => 'email',
                'error' => 'Email invalide'
            )
        ));

        return $validations;
    }

    /**
     * Set default properties values
     */
    public function setDefaultProperties()
    {
        parent::setDefaultProperties();

        $this->active = 1;
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
}
