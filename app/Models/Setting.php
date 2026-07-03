<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['credit_card_percentage', 'whatsapp_number'];

    /**
     * Default WhatsApp number (as displayed) used when none is configured.
     */
    public const DEFAULT_WHATSAPP_DISPLAY = '+1 212-660-4653';

    /**
     * Memoized settings row so the popup and footer share a single query per request.
     */
    protected static $current = null;

    public static function current(): ?self
    {
        if (static::$current === null) {
            static::$current = static::first() ?: false;
        }

        return static::$current ?: null;
    }

    /**
     * The WhatsApp number as entered by the admin (for display), or the default.
     */
    public static function whatsappDisplay(): string
    {
        $value = optional(static::current())->whatsapp_number;

        return $value !== null && trim($value) !== '' ? $value : self::DEFAULT_WHATSAPP_DISPLAY;
    }

    /**
     * Digits-only WhatsApp number for building a wa.me/ link.
     */
    public static function whatsappNumber(): string
    {
        return preg_replace('/\D/', '', static::whatsappDisplay());
    }
}
