<?php
namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;

trait Deactivatable
{
    public static function addIsActiveField(Blueprint $table)
    {
        $table->boolean('is_active')->default(false);
    }
}
