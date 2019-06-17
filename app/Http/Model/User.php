<?php

namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table='userss';
    public $timestamps =false;
    protected $primaryKey='u_id';
}