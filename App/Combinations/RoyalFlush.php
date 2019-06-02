<?php

namespace App\Combinations;

use App\Cards\Card;

class RoyalFlush extends AbstractCombination
{

    public function getName(): string
    {
        return 'Royal Flush';
    }

    public function getLevel(): int
    {
        return 10;
    }

    public function parseCards(): bool
    {
        $cards = [];

        foreach (array_merge($this->gamerCards, $this->boardCard) as $card) {
            /**
             * @var Card $card
             * */
            $cards[$card->getSuit()][$card->getLevel()] = $card;
        }

        $cards = array_filter($cards, function($group) {
            return count($group) >= 5;
        });

        $cards = array_shift($cards);

        if (!empty($cards[10]) && !empty($cards['j']) && !empty($cards['q']) && !empty($cards['k']) && !empty($cards['a'])) {
            $this->combinationCard[$cards[10]->getAlias()] = $cards[10];
            $this->combinationCard[$cards['j']->getAlias()] = $cards['j'];
            $this->combinationCard[$cards['q']->getAlias()] = $cards['q'];
            $this->combinationCard[$cards['k']->getAlias()] = $cards['k'];
            $this->combinationCard[$cards['a']->getAlias()] = $cards['a'];

            return true;
        }

        return false;
    }
}