<?php

namespace App\Combinations;

use App\Cards\Card;

class StraightFlush extends AbstractCombination
{

    public function getName(): string
    {
        return 'Straight Flush';
    }

    public function getLevel(): int
    {
        return 9;
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

        if (count($cards) >= 5) {
            foreach ($cards as $card) {
                if ($card->getLevel() == 'a' && !empty($cards[2])) {
                    $this->combinationCard[$card->getAlias()] = $card;
                    $this->combinationCard[$cards[2]->getAlias()] = $cards[2];
                } elseif ($card->getLevel() == 'k' && !empty($cards['a'])) {
                    $this->combinationCard[$card->getAlias()] = $card;
                    $this->combinationCard[$cards['a']->getAlias()] = $cards['a'];
                } elseif ($card->getLevel() == 'q' && !empty($cards['k'])) {
                    $this->combinationCard[$card->getAlias()] = $card;
                    $this->combinationCard[$cards['k']->getAlias()] = $cards['k'];
                } elseif ($card->getLevel() == 'j' && !empty($cards['q'])) {
                    $this->combinationCard[$card->getAlias()] = $card;
                    $this->combinationCard[$cards['q']->getAlias()] = $cards['q'];
                } elseif($card->getLevel() == 10 && !empty($cards['j'])) {
                    $this->combinationCard[$card->getAlias()] = $card;
                    $this->combinationCard[$cards['j']->getAlias()] = $cards['j'];
                } elseif(is_numeric($card->getLevel()) && !empty($cards[$card->getLevel()+1])) {
                    $this->combinationCard[$card->getAlias()] = $card;
                    $this->combinationCard[$cards[$card->getLevel()+1]->getAlias()] = $cards[$card->getLevel()+1];
                }
            }

            if (count($this->combinationCard) == 5) {
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