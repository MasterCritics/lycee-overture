<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Card;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\JoinClause;

class CardBuilderFactory
{
    private $card;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    public function createBuilderWithQuery(string $locale, array $query): Builder
    {
        /** @var Builder $builder */
        $builder = $this->card->select(['cards.*'])
            ->join(
                'card_translations as t',
                function (JoinClause $join) use ($locale) {
                    $join->on('cards.id', '=', 't.card_id')
                        ->where('t.locale', '=', $locale);
                }
            );

        if ($set = ($query['set'] ?? null)) {
            $builder->join(
                'card_sets AS cs',
                function (JoinClause $join) use ($set): void {
                    $join
                        ->on(new Expression('FIND_IN_SET(cards.id, cs.cards)'), '>', new Expression('0'))
                        ->where('cs.id', '=', $set);
                }
            );
        }

        if ($cardId = ($query['cardId'] ?? null)) {
            // Card IDs are comma-separated, and only the number bits from each value matters,
            // so the LO- and padding numbers are optional.
            $cardIds = array_map(
                function (string $cardId): string {
                    return sprintf('LO-%04d', preg_replace('/\D/', '', $cardId));
                },
                explode(',', $cardId)
            );
            $builder->whereIn('cards.id', $cardIds);
        }

        return $builder;
    }
}