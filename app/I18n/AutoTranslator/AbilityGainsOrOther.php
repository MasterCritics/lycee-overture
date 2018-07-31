<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Action;
use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

/**
 * For characters gaining abilities, or being discarded, or other.
 */
class AbilityGainsOrOther
{
    public static function autoTranslate(string $text): string
    {
        $subjectRegex = Subject::getUncapturedRegex();
        // language=regexp
        $getsSomethingActionRegex = 'は((?:\[.+?\])+)を得る|を(破棄|未行動に|行動済みに|手札に入|登場)(れる|する|できる)';

        // "This character gains X."
        $pattern = "/($subjectRegex)($getsSomethingActionRegex)/u";
        $text = Action::subjectReplaceCallback(
            $pattern,
            [self::class, 'callback'],
            $text
        );

        return $text;
    }

    public static function callback(array $matches): Action
    {
        $action = next($matches);
        $what = next($matches);
        $state = next($matches);
        $canOrDoSource = next($matches);
        $mandatory = true;
        if ($canOrDoSource === 'できる') {
            $mandatory = false;
        }
        $s = SentencePart\Action::THIRD_PERSON_PLURAL_PLACEHOLDER;
        $verb = $mandatory ? "get$s" : 'can be';
        switch ($state) {
            case '破棄':
                $doesAction = "$verb destroyed";
                break;
            case '未行動に':
                $doesAction = "$verb untapped";
                break;
            case '行動済みに':
                $doesAction = "$verb tapped";
                break;
            case '手札に入':
                $doesAction = "$verb returned to hand";
                break;
            case '登場':
                $doesAction = "$verb summoned";
                break;
            default:
                $verb = $mandatory ? "gain$s" : "can gain";
                if (isset($what)) {
                    $doesAction = "$verb $what";
                } else {
                    throw new \InvalidArgumentException("Unexpected action: $action");
                }
        }

        return new Action("$doesAction", false, false);
    }
}
