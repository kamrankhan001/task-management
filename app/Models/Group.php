<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(get: fn(string $value) => Carbon::make($value)->format('j M, Y'));
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
