<?php

namespace App\Models;

use Chiiya\FilamentAccessControl\Models\FilamentUser;

class AdminUser extends FilamentUser
{
    /**
     * Return a name.
     *
     * Needed for compatibility with filament-logger.
     */
    public function getNameAttribute(): string
    {
        return 'filament';
    }
}
