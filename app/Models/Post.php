<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    const STATUSES = [
        'open' => 'Abierto',
        'in_progress' => 'En Progreso',
        'resolved' => 'Resuelto',
        'closed' => 'Cerrado'
    ];

    const PRIORITIES = [
        'low' => 'Baja',
        'medium' => 'Media',
        'high' => 'Alta',
        'urgent' => 'Urgente'
    ];

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
        'assigned_to',
        'technology',
        'application',
        'version',
        'year',
    ];

    protected $casts = [
        'status' => 'string',
        'priority' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function solutions()
    {
        return $this->hasMany(Solution::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
