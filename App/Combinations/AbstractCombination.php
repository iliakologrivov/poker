<?php

namespace App\Combinations;

abstract class AbstractCombination {

    protected $combinationCard = [];
    protected $gamerCards = [];
    protected $boardCard = [];
    protected $usesCards = [];

    public function __construct(array $boardCard, array $cards)
    {
        $this->boardCard = $boardCard;
        $this->gamerCards = $cards;
    }

    abstract public function getName(): string;

    abstract public function parseCards(): bool;

    abstract public function getLevel(): int;

    public function getCombinationCard(): array
    {
        if (count($this->combinationCard) < 5) {
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
        }

        uasort($this->combinationCard, function($a, $b) {
            if ($a->getValue() == $b->getValue()) {
                return $a->getSuit() < $b->getSuit() ? -1 : 1;
            }

            return ($a->getValue() < $b->getValue()) ? -1 : 1;
        });

        return $this->combinationCard;
    }

    public function getUsesCards(): array
    {
        $this->usesCards = array_filter($this->combinationCard, function ($item) {
            return isset($this->gamerCards[$item->getAlias()]);
        });

        uasort($this->usesCards, function($a, $b) {
            if ($a->getValue() == $b->getValue()) {
                return $a->getSuit() < $b->getSuit() ? -1 : 1;
            }

            return ($a->getValue() > $b->getValue()) ? -1 : 1;
        });

        return $this->usesCards;
    }
}