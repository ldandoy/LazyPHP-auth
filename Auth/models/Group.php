<?php

namespace Auth\models;

use Core\Model;

class Group extends Model
{
    protected $permittedColumns = array(
        'code',
        'label'
    );

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
}