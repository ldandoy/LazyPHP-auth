<?php

namespace Auth\models;

use Core\Model;

class Group extends Model
{
    protected $permittedColumns = array(
        'label'
    );

    public function getValidations()
    {
        $validations = parent::getValidations();

        $validations = array_merge($validations, array(
            'label' => array(
                'type' => 'required',
                'error' => 'Label obligatoire'
            )
        ));
    }
}