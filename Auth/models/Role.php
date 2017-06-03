<?php

namespace Auth\models;

use Core\Model;

class Role extends Model
{
    protected $permittedColumns = array('controller', 'action');
}