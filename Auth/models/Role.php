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

    public static function checkAdministratorPermission($administrator, $roleCode)
    {
        if ($administrator !== null && $administrator->group_id !== null) {
            $roleAssignments = array_merge(
                RoleAssignment::findByGroup($administrator->group_id),
                RoleAssignment::findByAdministrator($administrator->id)
            );

            if (!empty($roleAssignments)) {
                foreach ($roleAssignments as $roleAssignment) {
                    if ($roleAssignment->role->code == $roleCode) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public static function checkUserPermission($user, $roleCode)
    {
        if ($user !== null && $user->group_id !== null) {
            $roleAssignments = array_merge(
                RoleAssignment::findByGroup($user->group_id),
                RoleAssignment::findByUser($user->id)
            );

            if (!empty($roleAssignments)) {
                foreach ($roleAssignments as $roleAssignment) {
                    if ($roleAssignment->role->code == $roleCode) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}