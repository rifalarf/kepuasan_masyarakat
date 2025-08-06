<?php

namespace App\Policies;

use App\Models\Unsur;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UnsurPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Unsur $unsur): bool
    {
        // Admin Utama HANYA bisa mengelola Unsur Global (village_id is null).
        if ($user->role === 'admin') {
            return $unsur->village_id === null;
        }

        // Admin Satker HANYA bisa mengelola Unsur milik Satker-nya (village_id cocok).
        if ($user->role === 'satker') {
            return (int) $unsur->village_id === (int) $user->village_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Unsur $unsur): bool
    {
        // Logika untuk hapus sama dengan update.
        return $this->update($user, $unsur);
    }
}