<?php

namespace App\Combinations;

use App\Cards\Card;

class FullHouse extends AbstractCombination
{

    public function getName(): string
    {
        return 'Full House';
    }

    public function getLevel(): int
    {
        return 7;
    }

    public function parseCards(): bool
    {
        $cards = [];

        foreach (array_merge($this->gamerCards, $this->boardCard) as $card) {
            /**
             * @var Card $card
             * */
            $cards[$card->getLevel()][] = $card;
        }

        $cards3 = array_filter($cards, function($group) {
            return count($group) == 3;
        });
        $cards3 = array_shift($cards3);

        if (! empty($cards3)) {
            $cards2 = array_filter($cards, function($group) {
                return count($group) == 2;
            });

            if (! empty($cards2)) {

                $cards2 = array_shift($cards2);

                array_push($this->combinationCard, ...$cards2);
                array_push($this->combinationCard, ...$cards3);

                return true;
            }
        }

        return false;
    }

    public function getCombinationCard(): array
    {
        uasort($this->combinationCard, function($a, $b) {
            if ($a->getValue() == $b->getValue()) {
                return $a->getSuit() < $b->getSuit() ? -1 : 1;
            }

            return ($a->getValue() < $b->getValue()) ? -1 : 1;
        });

        return $this->combinationCard;
    }
}