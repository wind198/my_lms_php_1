<?php
namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;

trait HasEntityDescriptiveFields
{

    public static $MAX_TITLE_LENGTH = 50;
    public static $MAX_DESCRIPTION_LENGTH = 250;
    public static function addTitleAndDescription(Blueprint $table)
    {
        $table->string('title', self::$MAX_TITLE_LENGTH);
        $table->string('description', self::$MAX_DESCRIPTION_LENGTH)->nullable();
    }
}
