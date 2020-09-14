<?php
namespace App\Models\Traits;

use App\Models\DatabaseNotification;

trait Notifiable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * Get the entity's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }
}