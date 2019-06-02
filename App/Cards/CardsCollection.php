<?php

namespace App\Cards;

class CardsCollection {

    protected $cards = [];

    public function __construct()
    {
        $cards = [2, 3, 4, 5, 6, 7, 8, 9, 10, 'j', 'q', 'k', 'a'];

        $suits = ['c', 'h', 'd', 's'];

        foreach ($suits as $suit) {
            foreach ($cards as $card) {
                $this->cards[$card.$suit] = $this->createCard($card.$suit, $suit, $card);
            }
        }
    }

    protected function createCard(string $alias, string $suit, string $level)
    {
        return new Card($alias, $suit, $level);
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function unsetCard(Card $card)
    {
        unset($this->cards[$card->getAlias()]);
    }
}