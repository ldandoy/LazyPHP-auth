<?php

namespace Auth\models;

use Core\Model;

class RoleAssignment extends Model
{
    protected $permittedColumns = array(
        'role_id',
        'group_id',
        'administrator_id',
        'user_id'
    );

    public function getAssociations()
    {
        return array(
            'role' => array(
                'type' => '1',
                'model' => 'Auth\\models\\Role',
                'key' => 'role_id'
            ),
            'group' => array(
                'type' => '1',
                'model' => 'Auth\\models\\Group',
                'key' => 'group_id'
            ),
            'administrator' => array(
                'type' => '1',
                'model' => 'Auth\\models\\Administrator',
                'key' => 'administrator_id'
            ),
            'user' => array(
                'type' => '1',
                'model' => 'Auth\\models\\User',
                'key' => 'user_id'
            )
        );
    }
}