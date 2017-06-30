<?php

namespace Auth\models;

use Core\Model;

class Role extends Model
{
    protected $permittedColumns = array(
        'code',
        'label'
    );

    public function getAssociations()
    {
        return array(
            'assignments' => array(
                'type' => '*',
                'model' => 'Auth\\models\\RoleAssignment',
                'key' => 'role_id'
            )
        );
    }

    public function getValidations()
    {
        $validations = parent::getValidations();

        $validations = array_merge($validations, array(
            'code' => array(
                'type' => 'required',
                'filters' => array('trim', 'lowercase'),
                'error' => 'Code obligatoire'
            ),
            'label' => array(
                'type' => 'required',
                'filters' => array('trim'),
                'error' => 'Nom obligatoire'
            )
        ));

        return $validations;
    }

    public static function check($role)
    {
        
    }
}