<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card\DeckTransformer;
use amcsi\LyceeOverture\Deck;

class DeckController extends Controller
{
    public function index(DeckTransformer $deckTransformer)
    {
        $decks = Deck::orderBy('name_en')->get();
        return $this->response->collection($decks, $deckTransformer);
    }
}
