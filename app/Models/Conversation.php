<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    public function sender()
    {
        return $this->morphTo();
    }

    public function topic()
    {
        return $this->morphTo();
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function scopeNewest(Builder $builder)
    {
        $builder->orderBy('updated_at', 'desc');
    }

    public function scopeWhereSender(Builder $builder, Model $sender)
    {
        $builder->where('sender_type', get_class($sender))
            ->where('sender_id', $sender->id);
    }

    public function scopeWhereTopic(Builder $builder, Model $topic)
    {
        $builder->where('topic_type', get_class($topic))
            ->where('topic_id', $topic->id);
    }

    public static function findByMessage(Message $message) {
        return static::whereSender($message->sender)->whereTopic($message->topic)->first();
    }
}
