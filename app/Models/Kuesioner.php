<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

class Kuesioner extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'unsur_id', 'village_id', 'start_date', 'end_date'];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::uuid4();
        });
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'kuesioner_id');
    }

    public function unsur(): BelongsTo
    {
        return $this->belongsTo(Unsur::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * Scope untuk kuesioner yang sedang aktif (dalam periode jadwal)
     */
    public function scopeActive($query)
    {
        $today = now()->toDateString();
        return $query->where(function($q) use ($today) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', $today);
        })->where(function($q) use ($today) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', $today);
        });
    }

    /**
     * Cek apakah kuesioner sedang aktif
     */
    public function isActive(): bool
    {
        $today = now()->toDateString();
        
        $startValid = is_null($this->start_date) || $this->start_date <= $today;
        $endValid = is_null($this->end_date) || $this->end_date >= $today;
        
        return $startValid && $endValid;
    }
}
