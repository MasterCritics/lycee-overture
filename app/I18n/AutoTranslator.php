<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

use amcsi\LyceeOverture\I18n\AutoTranslator\AbilityGainsOrOther;
use amcsi\LyceeOverture\I18n\AutoTranslator\CannotBeDestroyed;
use amcsi\LyceeOverture\I18n\AutoTranslator\CapitalizationFixer;
use amcsi\LyceeOverture\I18n\AutoTranslator\DiscardFromDeck;
use amcsi\LyceeOverture\I18n\AutoTranslator\DrawCards;
use amcsi\LyceeOverture\I18n\AutoTranslator\Equip;
use amcsi\LyceeOverture\I18n\AutoTranslator\FullWidthCharacters;
use amcsi\LyceeOverture\I18n\AutoTranslator\IfCardsInHand;
use amcsi\LyceeOverture\I18n\AutoTranslator\MoveCharacter;
use amcsi\LyceeOverture\I18n\AutoTranslator\QuoteTranslator;
use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Action;
use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;
use amcsi\LyceeOverture\I18n\AutoTranslator\StatChanges;
use amcsi\LyceeOverture\I18n\AutoTranslator\Target;
use amcsi\LyceeOverture\I18n\AutoTranslator\TurnAndBattle;
use amcsi\LyceeOverture\I18n\AutoTranslator\WhenSomething;

/**
 * Auto-translates parts of Japanese text.
 */
class AutoTranslator
{
    private static $punctuationMap = [
        '。' => '.',
        '、' => ',',
        '・' => ',',
    ];
    private $quoteTranslator;

    public function __construct(QuoteTranslator $quoteTranslator)
    {
        $this->quoteTranslator = $quoteTranslator;
    }

    public function autoTranslate(string $japaneseText): string
    {
        $bracketCounts = self::countBrackets($japaneseText);
        $subjectRegex = Subject::getUncapturedRegex();

        $autoTranslated = $japaneseText;

        // Quoted translation must happen before full-width characters are replaced.
        $autoTranslated = $this->quoteTranslator->autoTranslate($autoTranslated);

        $autoTranslated = preg_replace('/。$/', '.', $autoTranslated);
        $autoTranslated = preg_replace_callback(
            '/。|、|・/',
            function ($match) {
                return self::$punctuationMap[$match[0]] . ' ';
            },
            $autoTranslated
        );
        $autoTranslated = FullWidthCharacters::translateFullWidthCharacters($autoTranslated);
        // Replace ー (longizing katakana) used in place of full width minus sign, but only if a number follows.
        // Also replace − (weird alternative dash).
        $autoTranslated = preg_replace('/[ー−](\d)/u', '-$1', $autoTranslated);

        $autoTranslated = AbilityGainsOrOther::autoTranslate($autoTranslated);

        // "... get $statChanges."
        $autoTranslated = StatChanges::autoTranslate($autoTranslated);

        $autoTranslated = str_replace('その宣言の解決は失敗する', 'negate its effect', $autoTranslated);
        $autoTranslated = str_replace(
            '相手キャラがダウンしたバトル終了時',
            'at the end of the battle when the other character is defeated',
            $autoTranslated
        );
        $autoTranslated = str_replace('このキャラのバトル中に使用する', 'use during battle involving this character', $autoTranslated);
        $autoTranslated = str_replace(
            'この能力は失われる',
            'this effect can be used only once while this card is on the field',
            $autoTranslated
        );
        $autoTranslated = str_replace(
            '相手の能力の宣言に対応して使用する.',
            'use when your opponent activates an ability.',
            $autoTranslated
        );
        $autoTranslated = str_replace(
            '同番号の能力は1ターンに1回まで処理可能',
            'this effect can only be used once per turn by cards of the same number',
            $autoTranslated
        );
        $autoTranslated = str_replace(
            '対戦キャラは次の相手のウェイクアップで未行動に戻らない',
            "the opponent's character does not get untapped at their next wake-up",
            $autoTranslated
        );
        $autoTranslated = str_replace(
            'バトルを中断する',
            "stop the battle",
            $autoTranslated
        );
        $autoTranslated = preg_replace_callback(
            '/相手は相手の手札を(\d)枚破棄する/',
            function (array $matches): string {
                $howMany = next($matches);
                $s = $howMany !== '1' ? 's' : '';
                return "Your opponent discards $howMany card$s from their hand";
            },
            $autoTranslated
        );
        $autoTranslated = preg_replace_callback(
            '/自分のデッキを(\d)枚回復する/',
            function (array $matches): string {
                $howMany = next($matches);
                $s = $howMany !== '1' ? 's' : '';
                return "Recover $howMany card$s to your deck";
            },
            $autoTranslated
        );
        $autoTranslated = preg_replace_callback(
            '/自分の手札を(\d)枚デッキの(上|下)に置く/',
            function (array $matches): string {
                $howMany = next($matches);
                $whereSource = next($matches);
                $s = $howMany !== '1' ? 's' : '';
                $where = $whereSource === '上' ? 'top' : 'bottom';
                return "Put $howMany card$s from your hand on the $where of your deck";
            },
            $autoTranslated
        );
        $autoTranslated = Action::subjectReplace(
            "/($subjectRegex)は, 行動済みでも防御キャラに指定できる/u",
            'can defend even while tapped',
            $autoTranslated
        );
        $autoTranslated = preg_replace('/((?:\[.+?\])+)を発生する\./u', 'you get $1.', $autoTranslated);
        $autoTranslated = WhenSomething::autoTranslate($autoTranslated);
        $autoTranslated = DrawCards::autoTranslate($autoTranslated);
        $autoTranslated = DiscardFromDeck::autoTranslate($autoTranslated);
        $autoTranslated = Target::autoTranslate($autoTranslated);
        $autoTranslated = CannotBeDestroyed::autoTranslate($autoTranslated);
        $autoTranslated = TurnAndBattle::autoTranslate($autoTranslated);
        $autoTranslated = IfCardsInHand::autoTranslate($autoTranslated);
        $autoTranslated = MoveCharacter::autoTranslate($autoTranslated);
        $autoTranslated = Equip::autoTranslate($autoTranslated);

        // Fix spaces before brackets
        $autoTranslated = preg_replace('/(?<=\w)[\[{(]/', ' $0', $autoTranslated);
        // Condense multiple spaces into one; trim.
        $autoTranslated = trim(preg_replace('/ {2,}/', ' ', $autoTranslated));
        // Fix spaces between brackets
        $autoTranslated = preg_replace('/(?<=[[{(])\s+|\s+(?=[\]})])/', '', $autoTranslated);

        // Fix capitalization.
        $autoTranslated = CapitalizationFixer::fixCapitalization($autoTranslated);

        if (self::countBrackets($autoTranslated) !== $bracketCounts) {
            throw new \LogicException("Bracket count mismatch.\nOriginal: $japaneseText\nTranslated: $autoTranslated");
        }

        return $autoTranslated;
    }

    /**
     * @param string $japaneseText
     * @return array
     */
    private static function countBrackets(string $japaneseText): array
    {
        $charsToLookAt = [ord('{') => 0, ord('}') => 0];
        $charCounts = array_intersect_key(count_chars($japaneseText, 1), $charsToLookAt);
        return $charCounts;
    }
}
