<?php

namespace Auth\models;

use Core\Model;

class GroupAssignment extends Model
{
    protected $permittedColumns = array(
        'group_id',
        'user_id',
        'site_id'
    );

    public function getAssociations()
    {
        return array(
            'group' => array(
                'type' => '1',
                'model' => 'Auth\\models\\Group',
                'key' => 'group_id'
            ),
            'user' => array(
                'type' => '1',
                'model' => 'Auth\\models\\User',
                'key' => 'user_id'
            )
        );
    }
    
    public static function findByGroup($group_id)
    {
        return self::findAll('group_id = '.$group_id);
    }

    public static function findByUser($user_id)
    {
        return self::findAll('user_id = '.$user_id);
    }
}