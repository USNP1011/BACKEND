<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class UserRoleModel extends Model
{
    protected $table = 'user_role';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'users_id',
		'role_id'
    ];
}