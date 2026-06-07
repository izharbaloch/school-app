<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'category_id',
        'publisher',
        'publish_year',
        'total_copies',
        'available_copies',
        'price',
        'shelf_location',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'total_copies' => 'integer',
        'available_copies' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    public function issues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function activeIssues()
    {
        return $this->hasMany(BookIssue::class)->where('status', 'issued');
    }

    public function isAvailable(): bool
    {
        return $this->available_copies > 0;
    }
}
