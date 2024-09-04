<?php
namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;

trait HasEntityDescriptiveFields
{
    public static function addTitleAndDescription(Blueprint $table)
    {
        $table->string('title', 50)->nullable();
        $table->text('description', )->nullable();
    }
}
