<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

/**
 * Types of cards.
 */
class Type
{
    public const CHARACTER = 0;
    public const ITEM = 1;
    public const EVENT = 2;

    public static function getAll(): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return (new \ReflectionClass(static::class))->getConstants();
    }
}
