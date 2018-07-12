<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * For "when something happens" triggers.
 */
class WhenSomething
{
    public static function autoTranslate(string $text): string
    {
        $text = WhenSupporting::autoTranslate($text);
        $text = WhenAppears::autoTranslate($text);
        $text = str_replace('味方キャラがエンゲージ登場したとき', 'when an ally character enters engagement', $text);
        $text = str_replace('このキャラが移動したとき', 'when this character moves', $text);
        return $text;
    }
}