<?php

namespace App\Combinations;

use App\Cards\Card;

class Four extends AbstractCombination
{

    public function getName(): string
    {
        return 'Four of a Kind';
    }

    public function getLevel(): int
    {
        return 8;
    }

    public function parseCards(): bool
    {
        $cards = [];

        foreach (array_merge($this->gamerCards, $this->boardCard) as $card) {
            /**
             * @var Card $card
             * */
            $cards[$card->getLevel()][] = $card;

            if (count($cards[$card->getLevel()]) == 4) {
                foreach($cards[$card->getLevel()] as $item) {
                    $this->combinationCard[$item->getAlias()] = $item;
                }

                return true;
            }
        }

        return false;
    }
}