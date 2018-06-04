<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import\CsvValueInterpreter;

use amcsi\LyceeOverture\Card\BasicAbility;
use amcsi\LyceeOverture\Card\Element;

/**
 * Converts markup in Japanese (e.g. [アグレッシブ] and [日]), normalizes and translates it.
 */
class MarkupConverter
{
    public static function convert(string $text): string
    {
        $text = preg_replace_callback('/\[([雪月花宙日無]+)\]/u', ['self', 'elementCallback'], $text);

        $japaneseToMarkup = BasicAbility::getJapaneseToMarkup();
        $japaneseBasicAbilitiesRegex = implode('|', array_keys($japaneseToMarkup));

        $text = preg_replace_callback(
            "/\\[($japaneseBasicAbilitiesRegex)(?=[\\]:])/u",
            ['self', 'basicAbilityCallback'],
            $text
        );

        return $text;
    }

    private static function elementCallback(array $matches)
    {
        $elements = preg_split('//u', $matches[1], -1, PREG_SPLIT_NO_EMPTY);
        $markupMap = Element::getElementToMarkupMap();
        foreach ($elements as $key => $value) {
            $elements[$key] = "[$markupMap[$value]]";
        }
        return implode('', $elements);
    }

    private static function basicAbilityCallback(array $matches)
    {
        $markupMap = BasicAbility::getJapaneseToMarkup();
        return '[' . $markupMap[$matches[1]];
    }
}