<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectedAccount extends Model
{
    protected $table = 'connected_accounts';

    protected $guarded = ['id'];
}
