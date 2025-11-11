<?php

namespace App\Domain;

use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CustomStoredEvent extends EloquentStoredEvent
{
    public static function boot(): void
    {
        parent::boot();

        static::creating(function (CustomStoredEvent $storedEvent) {

            $storedEvent->meta_data['user_id'] = Auth::id();
            $storedEvent->meta_data['ip_address'] = Request::ip();
            $storedEvent->meta_data['request_path'] = Request::path();
            $storedEvent->meta_data['user_agent'] = Request::userAgent();
            $storedEvent->meta_data['created_at'] = now()->toDateTimeString();
            unset($storedEvent->meta_data['created-at']);
        });
    }
}
