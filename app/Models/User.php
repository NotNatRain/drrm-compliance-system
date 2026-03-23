<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'school_id',
        'typhoon_school_id',
        'incident_school_id',
        'needs_fs_registration',
        'needs_tf_registration',
        'module_access',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the school that the user belongs to (Fire Safety).
     */
    public function school()
    {
        return $this->belongsTo(FireSafetySchool::class, 'school_id');
    }

    /**
     * Get the evacuation center that the user belongs to (Typhoon).
     */
    public function typhoonSchool()
    {
        return $this->belongsTo(TypFldEvacuationCenter::class, 'typhoon_school_id');
    }

    /**
     * Get the incident school assigned to the user.
     */
    public function incidentSchool()
    {
        return $this->belongsTo(IncidentSchool::class, 'incident_school_id');
    }

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
            'module_access' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        // Instead of the token, we'll store a 6-digit code in the password_resets table
        $code = random_int(100000, 999999);
        
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $this->email],
            [
                'token' => \Hash::make($code),
                'created_at' => now()
            ]
        );

        $this->notify(new \App\Notifications\VerifyCodeNotification($code));
    }
}
