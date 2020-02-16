<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\CommentTranslator;

use amcsi\LyceeOverture\I18n\CommentTranslator\CommentTranslator;
use amcsi\LyceeOverture\I18n\SetTranslator\SetTranslator;
use PHPUnit\Framework\TestCase;

class CommentTranslatorTest extends TestCase
{
    /**
     * @dataProvider provideTranslate
     */
    public function testTranslate($expected, $input)
    {
        self::assertSame(
            $expected,
            (new CommentTranslator(
                new SetTranslator(require __DIR__ . '/../../../../resources/lang/en/sets.php')
            ))->translate($input)
        );
    }

    public function provideTranslate()
    {
        return [
            [
                'Deck restriction: August（[AUG]）',
                '構築制限:オーガスト（[AUG]）',
            ],
            [
                'Deck restriction: Girls & Panzer（[GUP]）',
                '構築制限:ガールズ＆パンツァー（[GUP]）',
            ],
            [
                'Deck restriction: Kamihime Project（[KHP]）',
                '構築制限:神姫PROJECT（[KHP]）',
            ],
            [
                'Deck restriction: Yuzusoft',
                '構築制限:ゆずソフト',
            ],
            [
                'Deck restriction: Oshiro Project: RE',
                '構築制限:御城プロジェクト：ＲＥ',
            ],
        ];
    }
}
