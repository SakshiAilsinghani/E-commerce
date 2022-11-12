<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use App\Transformers\UserTransformer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    

    const VERIFIED_USER = true;
    const UNVERIFIED_USER = false;

    public $transformer = UserTransformer::class;

    const ADMIN_USER = true;
    CONST REGULAR_USER = false;

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        self::created(function(User $user) {
            retry(5, function () use($user) {
                Mail::to($user)->send(new UserCreated($user));
            }, 250);

        });


        self::updated(function (User $user) {
            if($user->isDirty('email')) {
                retry(5, function () use($user) {
                    Mail::to($user)->send(new UserMailChanged($user));
                }, 250);

            }
        });

    }



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
        'password',
        'remember_token',
        'verification_token'
        

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isVerified()
    {
        return (bool)$this->verified === self::VERIFIED_USER;
    }

    public function isAdmin()
    {
        return (bool)$this->admin === self::ADMIN_USER;
    }

    public static function generateVerificationCode()
    {
        return Str::random(40);
    }

    
    public function getNameAttribute()
    {
        return ucwords($this->attributes['name']);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }


}
