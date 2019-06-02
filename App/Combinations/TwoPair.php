<?php

namespace App\Combinations;

use App\Cards\Card;

class TwoPair extends AbstractCombination
{

    public function getName(): string
    {
        return 'Two pair';
    }

    public function getLevel(): int
    {
        return 3;
    }

    public function parseCards(): bool
    {
        $cards = [];
        $pars = [];

        foreach (array_merge($this->gamerCards, $this->boardCard) as $card) {
            /**
             * @var Card $card
             * */
            $cards[$card->getLevel()][] = $card;

            if (count($cards[$card->getLevel()]) == 2) {
                foreach($cards[$card->getLevel()] as $item) {
                    $pars[$item->getAlias()] = $item;
                }
            }
        }

        if (count($pars) == 4) {
            $notUsesCards = array_diff_key(array_merge($this->gamerCards, $this->boardCard), $pars);

            uasort($notUsesCards, function($a, $b) {
                if ($a->getValue() == $b->getValue()) {
                    return $a->getSuit() > $b->getSuit() ? -1 : 1;
                }

                return ($a->getValue() > $b->getValue()) ? -1 : 1;
            });

            $pars[] = array_shift($notUsesCards);

            $this->combinationCard = $pars;

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