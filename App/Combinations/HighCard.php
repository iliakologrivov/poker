<?php

namespace App\Combinations;

class HighCard extends AbstractCombination {

    public function getName(): string
    {
        return 'High card';
    }

    public function getLevel(): int
    {
        return 1;
    }

    public function parseCards(): bool
    {
        $cards = array_merge($this->gamerCards, $this->boardCard);

        uasort($cards, function($a, $b) {
            if ($a->getValue() == $b->getValue()) {
                return 0;
            }

            return ($a->getValue() < $b->getValue()) ? -1 : 1;
        });

        array_shift($cards);

        $this->combinationCard = $cards;

        $this->usesCards = array_filter($this->combinationCard, function($item) {
            return isset($this->gamerCards[$item->getAlias()]);
        });

        return true;
    }
}