<?php

namespace App\Combinations;

use App\Cards\Card;

class OnePair extends AbstractCombination
{

    public function getName(): string
    {
        return 'One pair';
    }

    public function getLevel(): int
    {
        return 2;
    }

    public function parseCards():bool
    {
        $cards = [];

        foreach (array_merge($this->gamerCards, $this->boardCard) as $card) {
            /**
             * @var Card $card
             * */
            $cards[$card->getLevel()][$card->getAlias()] = $card;

            if (count($cards[$card->getLevel()]) == 2) {
                $this->combinationCard = $cards[$card->getLevel()];

                $this->usesCards = array_filter($this->combinationCard, function ($item) {
                    return isset($this->gamerCards[$item->getAlias()]);
                });

                return true;
            }
        }

        return false;
    }

    public function getUsesCards(): array
    {
        return  $this->usesCards;
    }
}