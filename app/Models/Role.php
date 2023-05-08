<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    const ROLE_ADMIN = 'admin';

    const ROLE_USER = 'user';



    protected $guarded = [
        'name',
        'guard_name'
    ];

}


