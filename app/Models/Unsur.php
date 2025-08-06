<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Unsur extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kuesioners(): HasMany
    {
        return $this->hasMany(Kuesioner::class);
    }

    // Relasi ke Satuan Kerja (Village)
    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * Scope a query to only include unsurs available for a given user.
     */
    public function scopeForUser(Builder $query): void
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin Utama HANYA bisa memilih dari Unsur Global
            $query->whereNull('village_id');
        } elseif ($user->role === 'satker') {
            // Satker bisa memilih dari unsur global DAN unsur miliknya sendiri
            $query->whereNull('village_id')->orWhere('village_id', $user->village_id);
        }
    }
}
