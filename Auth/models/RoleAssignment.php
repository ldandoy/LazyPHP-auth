<?php

namespace Auth\models;

use Core\Model;

class RoleAssignment extends Model
{
    protected $permittedColumns = array(
        'role_id',
        'group_id',
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
            'user' => array(
                'type' => '1',
                'model' => 'Auth\\models\\User',
                'key' => 'user_id'
            )
        );
    }

    public static function findAllSorted()
    {
        $roleAssignments = self::findAll();

        $res = array(
            'groups' => array(),
            'users' => array()
        );

        foreach ($roleAssignments as $roleAssignment) {
            if ($roleAssignment->group_id !== null) {
                if (isset($res['groups'][$roleAssignment->group_id])) {
                    $res['groups'][$roleAssignment->group_id][] = $roleAssignment->role_id;
                } else {
                    $res['groups'][$roleAssignment->group_id] = array($roleAssignment->role_id);
                }
            } else if ($roleAssignment->user_id !== null) {
                if (isset($res['users'][$roleAssignment->user_id])) {
                    $res['users'][$roleAssignment->user_id][] = $roleAssignment->role_id;
                } else {
                    $res['users'][$roleAssignment->user_id] = array($roleAssignment->role_id);
                }
            }
        }

        return $res;
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