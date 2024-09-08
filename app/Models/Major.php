<?php

namespace App\Models;

use App\Traits\HasEntityDescriptiveFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
    ];

    public static function getRules()
    {
        return [
            'title' => ['required', 'string', 'max:' . HasEntityDescriptiveFields::$MAX_TITLE_LENGTH],
            'description' => ['nullable', 'string', 'max:' . HasEntityDescriptiveFields::$MAX_DESCRIPTION_LENGTH],
        ];
    }
}
