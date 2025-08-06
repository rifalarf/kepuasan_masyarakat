<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

class Village extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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

    public function satkerType(): BelongsTo
    {
        return $this->belongsTo(SatkerType::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function respondens(): HasMany
    {
        return $this->hasMany(Responden::class);
    }

    /**
     * TAMBAHKAN METHOD INI
     * Mendefinisikan relasi "has many" ke Kuesioner.
     */
    public function kuesioners(): HasMany
    {
        return $this->hasMany(Kuesioner::class);
    }
}
