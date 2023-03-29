<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class OnScreenNotificationService
{
    /**
     * Notifications ready to be displayed
     *
     * @var array
     */
    private static $notifications = [];

    private const SESSION_KEY = 'notifications';

    /**
     * Add one or more notifications and return array ready to be passed to redirect()->with() or view()->with()
     */
    public static function with(?array $notificationOrNotifications = [], ?array $with = []): array
    {
        self::add($notificationOrNotifications);

        return array_merge(
            $with,
            [ static::SESSION_KEY => self::$notifications ]
        );
    }

    /**
     * Add a notification
     */
    public static function add(array $notificationOrNotifications): void
    {
        $notifications = self::merge(self::parse($notificationOrNotifications));

        self::$notifications = $notifications;
    }

    /**
     * Flash one or more notifications to Session
     */
    public static function flash(array $notificationOrNotifications): void
    {
        $notifications = self::merge(self::parse($notificationOrNotifications));

        Session::flash(static::SESSION_KEY, $notifications);
    }

    /**
     * Parse in input array of one or more notifications,
     *  and return a sanitized array of notification objects
     */
    public static function parse(array $notificationOrNotifications): array
    {
        if (Arr::isAssoc($notificationOrNotifications)) {
            $notifications = [ $notificationOrNotifications ];
        } else {
            $notifications = $notificationOrNotifications;
        }

        return collect($notifications)
            ->map(function ($notification) {
                return self::sanitize($notification);
            })
            ->toArray();
    }

    /**
     * Ensure that all values are present for a notification, defaulting as needed
     */
    private static function sanitize(array $notification): object
    {
        $level = self::level($notification['level'] ?? null);

        return (object)[
            'id' => $notification['id'] ?? null,
            'level' => $level,
            'message' => $notification['message'],
            'additionalInfo' => $notification['additionalInfo'] ?? null,
            'buttons' => $notification['buttons'] ?? [],
            'prefix' => $notification['prefix'] ?? self::prefix($notification['level'] ?? null),
        ];
    }

    /**
     * Return level, defaulting as needed
     */
    public static function level(?string $level): string
    {
        return $level ?? 'info';
    }

    /**
     * Return prefix, given a level
     */
    public static function prefix(?string $level = null): string
    {
        return ($level === 'success' ? 'Success!' : null)
            ?? ($level === 'error' ? 'Oops!' : null)
            ?? ($level === 'warning' ? 'FYI...' : null)
            ?? ($level === 'info' ? 'Heads Up!' : null)
            ?? '';
    }

    /**
     * Merge new notifications in with existing
     * If any entries have an `id` that matches the `id` of an existing entry, it will overwrite the old
     */
    private static function merge(array $notifications): array
    {
        return collect($notifications)->reduce(function ($acc, $notification) {
            if ($notification->id) {
                $existingIndex = collect($acc)->search(function ($item) use ($notification) {
                    return $item->id === $notification->id;
                });

                if ($existingIndex) {
                    $acc[$existingIndex] = $notification;

                    return $acc;
                }
            }

            $acc[] = $notification;

            return $acc;
        }, self::$notifications);
    }

    /**
     * How many notifications are queued?
     */
    public static function count(): int
    {
        return count(self::get());
    }

    /**
     * Return all notifications for display
     */
    public static function get(): array
    {
        return Session::get(static::SESSION_KEY, []);
    }

    /**
     * Return first error for display
     */
    public static function firstError(): ?string
    {
        if (!self::count()) {
            return null;
        }

        $error = collect(self::get())->firstWhere('level', 'error');

        return $error ? $error->message : null;
    }
}
