<?php

namespace App\Combinations;

use App\Cards\Card;

class Flush extends AbstractCombination
{

    public function getName(): string
    {
        return 'Flush';
    }

    public function getLevel(): int
    {
        return 6;
    }

    public function parseCards(): bool
    {
        $cards = [];

        foreach (array_merge($this->gamerCards, $this->boardCard) as $card) {
            /**
             * @var Card $card
             * */
            $cards[$card->getSuit()][] = $card;
        }

        $cards = array_filter($cards, function($group) {
            return count($group) >= 5;
        });

        $cards = array_shift($cards);

        if (count($cards) >= 5) {
            uasort($cards, function($a, $b) {
                if ($a->getValue() == $b->getValue()) {
                    return $a->getSuit() < $b->getSuit() ? -1 : 1;
                }

                return ($a->getValue() < $b->getValue()) ? -1 : 1;
            });

            $this->combinationCard = array_slice($cards, -5);
        };

        if (! empty($this->combinationCard)) {
            $this->usesCards = array_filter($this->combinationCard, function($item) {
                return isset($this->gamerCards[$item->getAlias()]);
            });

            return true;
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