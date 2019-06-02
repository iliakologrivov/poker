<?php

namespace App\Combinations;

class CombinationsCollection {

    protected $boardCard = [];

    public $combinations = [
        RoyalFlush::class,
        StraightFlush::class,
        Four::class,
        FullHouse::class,
        Flush::class,
        Straight::class,
        Three::class,
        TwoPair::class,
        OnePair::class,
        HighCard::class,
    ];

    public function __construct(array $boardCards)
    {
        $this->boardCard = $boardCards;
    }

    public function runSearch(array $cards)
    {
        foreach ($this->combinations as $combination) {
            $combination = new $combination($this->boardCard, $cards);
            if ($combination->parseCards()) {
                return $combination;
            }
        }
    }
}