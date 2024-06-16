<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'picture', 'is_admin'];

    protected $hidden = ['password', 'remember_token'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(get: fn(string $value) => Carbon::make($value)->format('j M, Y'));
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

}
