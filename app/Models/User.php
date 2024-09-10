<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    public static $SUPER_ADMIN_ROLE = 'super_admin';
    public static $STUDENT_ROLE = 'student';
    public static $TEACHER_ROLE = 'teacher';
    public static $MAX_FIRST_NAME_LENGTH = 50;
    public static $MAX_LAST_NAME_LENGTH = 50;
    public static $MAX_EMAIL_LENGTH = 50;
    public static $MAX_FULL_NAME_LENGTH = 100;
    public static $MAX_PHONE_LENGTH = 50;
    public static $EDUCATION_HIGHSCHOOL = 'high_school';
    public static $EDUCATION_BACHELOR = 'bachelor';
    public static $EDUCATION_MASTER = 'master';

    public static $GENDER_MALE = 'male';
    public static $GENDER_FEMALE = 'female';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'generation_id',
        'gender',
        'dob',
        'first_name',
        'last_name',
        'full_name',
        'email',
        'password',
        'phone',
        'address',
        'user_type',
        'note',
        'education_background'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function generateRandomPassword(): string
    {
        return fake()->password(8, 20); // Generates a random password between 8 and 20 characters
    }


    public static function getRules()
    {
        return [
            'first_name' => ['required', 'string', 'max:' . User::$MAX_FIRST_NAME_LENGTH],
            'last_name' => ['required', 'string', 'max:' . User::$MAX_LAST_NAME_LENGTH],
            'email' => ['required', 'string', 'email', 'max:' . User::$MAX_EMAIL_LENGTH, 'unique:users'],
            'phone' => ['nullable', 'string', 'max:' . User::$MAX_PHONE_LENGTH],
            'education_background' => [
                'required',
                'string',
                'in:' . implode(',', [
                    User::$EDUCATION_HIGHSCHOOL,
                    User::$EDUCATION_BACHELOR,
                    User::$EDUCATION_MASTER
                ])
            ],
            'generation_id' => [
                'nullable', // Make this field optional if needed
                'exists:generations,id' // Ensure the generation_id exists in the generations table
            ],
        ];
    }


    public function generation(): BelongsTo
    {
        return $this->belongsTo(Generation::class);
    }
}
