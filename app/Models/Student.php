<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Student extends Authenticatable
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'name',
        'nickname',
        'father_name',
        'mother_name',
        'phone',
        'email',
        'address',
        'facebook_acc_name',
        'viber_phone',
        'telegram_username',
        'date_of_birth',
        'nrc',
        'gender',
        'education',
        'native_town',
        'religious_status',
        'race',
        'image',
        'username',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'register_date' => 'date',
        'enroll_date' => 'date',
    ];

    /**
     * Enrolled courses with the section the student attends and the enroll date.
     */
    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'student_id');
    }

    public function activeEnrollments()
    {
        return $this->enrollments()->active();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    /**
     * Classes this student left before they finished. One row per event, so this
     * can hold more than one: dropping out, coming back and dropping out again is
     * a real sequence and all of it is worth keeping.
     */
    public function dropOuts()
    {
        return $this->hasMany(DropOut::class, 'student_id');
    }

    /**
     * Certificates issued to this student, collected or still waiting.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'student_id');
    }

    /**
     * Marks recorded against this student, one per subject per sitting.
     */
    public function gradingResults()
    {
        return $this->hasMany(GradingResult::class, 'student_id');
    }

    /**
     * Does this student hold an active enrollment for the given class?
     * This is the rule that gates attendance marking.
     */
    public function hasActiveEnrollmentFor(int $courseId, int $sectionId): bool
    {
        return $this->enrollments()
            ->active()
            ->where('course_id', $courseId)
            ->where('section_id', $sectionId)
            ->exists();
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(
            Course::class,
            'student_enrollments',
            'student_id',
            'course_id'
        )->withPivot(['section_id', 'enroll_date']);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Username = first 3 letters of the name + "yha" + 2 random digits.
     * e.g. "Aye Yha Htun" -> "Ayeyha13"
     */
    public static function generateUsername(?string $name): string
    {
        $letters = preg_replace('/[^a-zA-Z]/', '', (string) $name);
        $prefix = Str::lower(Str::substr($letters ?: 'stu', 0, 3));

        do {
            $username = $prefix . 'yha' . random_int(10, 99);
        } while (static::where('username', $username)->exists());

        return $username;
    }

    /**
     * Password = "yha" + 5 random digits. e.g. "yha12943"
     */
    public static function generatePassword(): string
    {
        return 'yha' . random_int(10000, 99999);
    }

    /**
     * Both credentials at once, used by the admin "Generate" button.
     */
    public static function generateCredentials(?string $name): array
    {
        return [
            'username' => static::generateUsername($name),
            'password' => static::generatePassword(),
        ];
    }
}
