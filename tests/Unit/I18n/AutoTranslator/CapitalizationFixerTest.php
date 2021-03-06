<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\CapitalizationFixer;
use PHPUnit\Framework\TestCase;

class CapitalizationFixerTest extends TestCase
{
    /**
     * @dataProvider provideFixCapitalization
     */
    public function testFixCapitalization(string $expected, string $input)
    {
        self::assertSame($expected, CapitalizationFixer::fixCapitalization($input));
    }

    public function provideFixCapitalization()
    {
        return [
            [
                'As',
                'as',
            ],
            [
                'As. As.',
                'as. as.',
            ],
            'ignore effect type' => [
                '[Continuous] As. As.',
                '[Continuous] as. as.',
            ],
            'ignore activate cost' => [
                '[Activate] [T][sun]: As. As.',
                '[Activate] [T][sun]: as. as.',
            ],
        ];
    }
}
