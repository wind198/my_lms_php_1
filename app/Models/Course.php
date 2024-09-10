<?php

namespace App\Models;

use App\Traits\HasEntityDescriptiveFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
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
        'major_id',
    ];

    public static function getRules()
    {
        return [
            'title' => ['required', 'string', 'max:' . HasEntityDescriptiveFields::$MAX_TITLE_LENGTH],
            'description' => ['nullable', 'string', 'max:' . HasEntityDescriptiveFields::$MAX_DESCRIPTION_LENGTH],
            'major_id' => [
                'nullable', // Make this field optional if needed
                'exists:majors,id' // Ensure the major_id exists in the majors table
            ],
        ];
    }
}
