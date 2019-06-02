<?php

namespace App\Combinations;

use App\Cards\Card;

class Three extends AbstractCombination
{

    public function getName(): string
    {
        return 'Three of a Kind';
    }

    public function getLevel(): int
    {
        return 4;
    }

    public function parseCards(): bool
    {
        $cards = [];

        foreach (array_merge($this->gamerCards, $this->boardCard) as $card) {
            /**
             * @var Card $card
             * */
            $cards[$card->getLevel()][] = $card;

            if (count($cards[$card->getLevel()]) == 3) {
                foreach($cards[$card->getLevel()] as $item) {
                    $this->combinationCard[$item->getAlias()] = $item;
                }
            }
        }

        if (count($this->combinationCard) == 3) {
            $notUsesCards = array_diff_key(array_merge($this->gamerCards, $this->boardCard), $this->combinationCard);

            uasort($notUsesCards, function($a, $b) {
                if ($a->getValue() == $b->getValue()) {
                    return $a->getSuit() > $b->getSuit() ? -1 : 1;
                }

                return ($a->getValue() > $b->getValue()) ? -1 : 1;
            });

            while(count($this->combinationCard) < 5) {
                $this->combinationCard[] = array_shift($notUsesCards);
            }

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