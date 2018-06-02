<?php
declare(strict_types=1);

namespace Tests\Unit\I18n;

use amcsi\LyceeOverture\I18n\AutoTranslator;
use PHPUnit\Framework\TestCase;

class AutoTranslatorTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, AutoTranslator::autoTranslate($input));
    }

    public function provideAutoTranslate()
    {
        return [
            [
                '',
                '',
            ],
            [
                'Asdf',
                'asdf',
            ],
            [ // Punctuation.
                'する. する, する.',
                'する。する、する。',
            ],
            [ // Full width numbers.
                '0',
                '０',
            ],
            [ // Full width numbers.
                '9',
                '９',
            ],
            [ // Full width letters.
                'Z',
                'Ｚ',
            ],
            [
                '..とき this character gets AP+2, DP+2.',
                '..ときこのキャラにＡＰ＋２・ＤＰ＋２する。',
            ],
            [
                'This [花] character gets SP+1.',
                'この[花]キャラにＳＰ＋１する。',
            ],
            [
                '味方キャラが登場したとき, this character gets AP+1, DP+1.',
                '味方キャラが登場したとき、このキャラにＡＰ＋１・ＤＰ＋１する。',
            ],
        ];
    }
}