<?php
namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;

trait HasEntityDescriptiveFields
{

    public static $MAX_TITLE_LENGTH = 50;
    public static $MAX_DESCRIPTION_LENGTH = 50;
    public static function addTitleAndDescription(Blueprint $table)
    {
        $table->string('title', 50);
        $table->string('description', 250)->nullable();
    }
}
