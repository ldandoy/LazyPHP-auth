<?php

namespace Auth\models;

use Core\Model;

class Roleassignment extends Model
{
    protected $permittedColumns = array(
        'group_id',
        'administrator_id',
        'user_id'
    );

    public function getAssociations()
    {
        return array(
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