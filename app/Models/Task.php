<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'due_date', 
        'priority',
        'status',
        'category_id',
        'user_id',
        'group_id',
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::make($value)->format('j M, Y'),
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(get: fn(string $value) => Carbon::make($value)->format('j M, Y'));
    }

    protected function dueDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::make($value)->format('j M, Y'),
            set: fn($value) => is_string($value) ? Carbon::parse($value) : $value,
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
