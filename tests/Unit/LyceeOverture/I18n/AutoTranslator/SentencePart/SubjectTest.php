<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator\SentencePart;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;
use PHPUnit\Framework\TestCase;

class SubjectTest extends TestCase
{
    /**
     * @dataProvider provideCreateInstance
     */
    public function testCreateInstance(string $expected, string $input): void
    {
        self::assertSame($expected, Subject::createInstance($input)->getSubjectText());
    }

    public function provideCreateInstance()
    {
        return [
            [
                ' all your AF characters',
                '味方AFキャラ全て',
            ],
            [
                ' that character',
                'そのキャラ',
            ],
            [
                ' 2 ally untapped characters',
                '未行動の味方キャラ2体',
            ],
            'item (even if it doesnt make sense' => [
                ' all your AF items',
                '味方AFアイテム全て',
            ],
            'event (even if it doesnt make sense' => [
                ' all your AF events',
                '味方AFイベント全て',
            ],
            'field as noun' => [
                ' an ally field',
                '味方フィールド',
            ],
            'with cost restriction' => [
                ' 1 ally character with a cost of 2 or less',
                'コストが2点以下の味方キャラ1体',
            ],
            'with cost restriction of more' => [
                ' 1 ally character with a cost of 2 or more',
                'コストが2点以上の味方キャラ1体',
            ],
            'with exact cost restriction' => [
                ' 1 ally character with a cost of 2',
                'コストが2点の味方キャラ1体',
            ],
            'with DP restriction' => [
                ' 1 ally character with a DP of 2 or less',
                'DPが2以下の味方キャラ1体',
            ],
            'with DP restriction exact' => [
                ' 1  character with a DP of 3',
                'DPが3のキャラ1体',
            ],
            "character's DP" => [
                " that character's SP",
                'そのキャラのSP',
            ],
            "1 item with sheet (枚) kanji" => [
                ' 1  item',
                'アイテム1枚',
            ],
            "1 character in the graveyard" => [
                ' 1  character in the graveyard',
                'ゴミ箱のキャラ1体',
            ],
            "1 of your events in the graveyard" => [
                ' 1  event in your graveyard',
                '自分のゴミ箱のイベント1枚',
            ],
            "that character in the graveyard" => [
                ' that character in the graveyard',
                'ゴミ箱のそのキャラ',
            ],
            "opponent's graveyard" => [
                ' 2  events in your opponent\'s graveyard',
                '相手のゴミ箱のイベント2枚',
            ],
            "this character's SP" => [
                " this character's SP",
                'このキャラのSP',
            ],
            "this character's SP and AP" => [
                " this character's SP and AP",
                'このキャラのSPとAP',
            ],
            'quoted noun' => [
                ' 1  "稲生滸" in your graveyard',
                '自分のゴミ箱の「稲生滸」1体',
            ],
            'gt/lt noun' => [
                ' 1  <稲生滸> in your graveyard',
                '自分のゴミ箱の<稲生滸>1体',
            ],
            'quoted noun with another noun' => [
                ' 1  <稲生滸> character in your graveyard',
                '自分のゴミ箱の<稲生滸>キャラ1体',
            ],
            'quoted 2' => [
                ' 1 ally <継続> character',
                '味方<継続>キャラ1体',
            ],
            'graveyard and cost combination' => [
                ' 1  character in your graveyard with a cost of 2 or less',
                '自分のゴミ箱のコストが2点以下のキャラ1体',
            ],
            'battling character' => [
                ' 1  character participating in battle',
                'バトル参加キャラ1体',
            ],
            'enemy item' => [
                ' 1 enemy item',
                '相手のアイテム1枚',
            ],
            'same row' => [
                ' 1 ally character in the same row as this character',
                'このキャラと同列の味方キャラ1体',
            ],
            'same column' => [
                ' 1 ally character in the same column as that character',
                'そのキャラと同オーダーの味方キャラ1体',
            ],
            'compound subject' => [
                ' this character and that character',
                'このキャラとそのキャラ',
            ],
            'compound subject or' => [
                ' this character or that character',
                'このキャラまたはそのキャラ',
            ],
        ];
    }
}
