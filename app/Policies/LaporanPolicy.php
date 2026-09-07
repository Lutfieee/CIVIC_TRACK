<?php

namespace App\Policies;

use App\Models\Laporan;
use App\Models\User;

class LaporanPolicy
{
    public function view(User $user, Laporan $laporan): bool
    {
        return $user->id === $laporan->user_id || $user->isAdmin();
    }

    public function update(User $user, Laporan $laporan): bool
    {
        return $user->id === $laporan->user_id && $laporan->status === 'pending';
    }

    public function delete(User $user, Laporan $laporan): bool
    {
        return $user->id === $laporan->user_id && $laporan->status === 'pending';
    }
}
