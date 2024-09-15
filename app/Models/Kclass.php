<?php

namespace App\Models;

use App\Traits\HasEntityDescriptiveFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Kclass extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'main_teacher_id',
        'course_id'
    ];

    // Validation rules for the model
    public static function getRules()
    {
        return [
            'title' => ['required', 'string', 'max:' . HasEntityDescriptiveFields::$MAX_TITLE_LENGTH],
            'description' => ['nullable', 'string', 'max:' . HasEntityDescriptiveFields::$MAX_DESCRIPTION_LENGTH],
            'course_id' => [
                'nullable', // Make this field optional if needed
                'exists:courses,id' // Ensure the major_id exists in the courses table
            ],
            'main_teacher_id' => [
                'nullable', // Make this field optional if needed
                'exists:users,id' // Ensure the main_teacher_id exists in the users table
            ],

        ];
    }

    // Many-to-many relationship with students (User)
    public function students()
    {
        return $this->belongsToMany(User::class, 'kclass_student', 'kclass_id', 'student_id');
    }

    // One-to-many relationship with main teacher
    public function mainTeacher()
    {
        return $this->belongsTo(User::class, 'main_teacher_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
