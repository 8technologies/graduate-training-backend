<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'category',
        'price',
        'capacity',
        'image_url',
        'organizer',
        'featured',
        'status',
        'tags'
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i',
        'price' => 'decimal:2',
        'featured' => 'boolean',
        'tags' => 'array'
    ];

    // protected $appends = ['registered_count', 'is_full', 'formatted_date', 'formatted_time'];

    // Relationships
    /* public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function activeRegistrations()
    {
        return $this->hasMany(EventRegistration::class)->where('status', 'registered');
    } */

    // Accessors
    /* public function getRegisteredCountAttribute()
    {
        return $this->activeRegistrations()->count();
    }

    public function getIsFullAttribute()
    {
        return $this->registered_count >= $this->capacity;
    }

    public function getFormattedDateAttribute()
    {
        return $this->event_date->format('M d, Y');
    } */

    /* public function getFormattedTimeAttribute()
    {
        return Carbon::createFromFormat('H:i:s', $this->event_time)->format('g:i A');
    } */

    // Scopes
    

    /* public function scopeByStatus(Builder $query, $status)
    {
        if ($status && $status !== 'All') {
            return $query->where('status', strtolower($status));
        }
        return $query;
    } */

    /* public function scopeSearch(Builder $query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%")
                  ->orWhereJsonContains('tags', $search);
            });
        }
        return $query;
    } */

    /* public function scopeSortBy(Builder $query, $sortBy)
    {
        switch ($sortBy) {
            case 'date':
                return $query->orderBy('event_date', 'asc')->orderBy('event_time', 'asc');
            case 'price':
                return $query->orderBy('price', 'asc');
            case 'popularity':
                return $query->withCount('activeRegistrations')->orderBy('active_registrations_count', 'desc');
            default:
                return $query->orderBy('event_date', 'asc');
        }
    }

    // Methods
    public function canRegister()
    {
        return $this->status === 'upcoming' && !$this->is_full && $this->event_date >= now()->toDateString();
    } */

    /* public function isRegistered($userId)
    {
        return $this->activeRegistrations()->where('user_id', $userId)->exists();
    } */
}


