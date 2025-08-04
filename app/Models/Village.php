<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Ramsey\Uuid\Uuid;

class Village extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'satker_type_id'];

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

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class);
    }

    public function satkerType()
    {
        return $this->belongsTo(SatkerType::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
