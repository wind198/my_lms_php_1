<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempKey extends Model
{
    use HasFactory;

    public static $FOR_RESET_PASSWORD = 'reset_password';
    public static $FOR_ACCOUNT_ACTIVATION = 'account_activation';

    public static function generateKey()
    {
        return fake()->uuid();
    }
}
