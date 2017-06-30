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

    public static function getOptions($parent_id = null)
    {
        $options = array(
            0 => array(
                'value' => '',
                'label' => '---'
            )
        );

        $groups = self::findAll();

        foreach ($groups as $group) {
            $options[$group->id] = array(
                'value' => $group->id,
                'label' => $group->label
            );
        }

        return $options;
    }
}