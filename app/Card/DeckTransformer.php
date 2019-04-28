<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Deck;
use amcsi\LyceeOverture\I18n\Locale;
use League\Fractal\TransformerAbstract;

class DeckTransformer extends TransformerAbstract
{
    public function transform(Deck $deck): array
    {
        return [
            'id' => $deck->id,
            'name' => \App::getLocale() === Locale::JAPANESE ? $deck->name_ja : $deck->name_en,
        ];
    }
}
