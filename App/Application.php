<?php

namespace App;

use App\Cards\CardsCollection;
use App\Combinations\AbstractCombination;
use App\Combinations\CombinationsCollection;

Class Application {

    protected $arguments;

    protected $cardsCollection = [];

    public function __construct(array $arguments)
    {
        $this->setArguments($arguments);
        $this->cardsCollection = new CardsCollection();
    }

    protected function parseCardsList($cardsList)
    {
        if (empty($cardsList)) {
            return [];
        }

        $cardsFound = '';
        $cards = [];

        foreach ($this->cardsCollection->getCards() as $card) {
            if (stristr($cardsList, $card->getAlias()) !== false) {
                $cards[$card->getAlias()] = $card;
                $this->cardsCollection->unsetCard($card);
                $cardsFound.= $card->getAlias();
            }
        }

        if (strlen($cardsFound) != strlen($cardsList)) {
            throw new \Exception('Карты дублированны или не найдены в колоде');
        }

        return $cards;
    }

    protected function setArguments(array $arguments)
    {
        array_shift($arguments);

        foreach ($arguments as $argument) {
            $argument =  explode('=', trim($argument));

            $this->setArgument($argument[0], $argument[1] ?? null);
        }
    }

    public function setArgument(string $key, $value = null)
    {
        $this->arguments[$key] = $value;
    }

    public function getArgument(string $key)
    {
        return $this->arguments[$key] ?? null;
    }

    public function run()
    {
        $boardCards = $this->parseCardsList($this->getArgument('--board'));

        if (empty($boardCards)) {
            throw new \Exception('На столе нет карт');
        }

        if (count($boardCards) > 5) {
            throw new \Exception('На столе больше 5 карт');
        }

        $gamers = [];

        $combinations = new CombinationsCollection($boardCards);
        for ($gameNumber = 1; $gameNumber<=10; $gameNumber++) {
            $gamerCard = $this->getArgument('--p' . $gameNumber);

            if (! empty($gamerCard)) {
                $gamerCard = $this->parseCardsList($gamerCard);

                if (count($gamerCard) != 2) {
                    throw new \Exception('У игрока не 2 карты');
                }

                $gamers[] = [
                    'name' => 'p' . $gameNumber,
                    'combination' => $combinations->runSearch($gamerCard)
                ];
            }
        }

        if (count($gamers) < 2) {
            throw new \Exception('Не может быть меньше 2х игроков');
        }

        uasort($gamers, function($a, $b) {
            if ($a['combination']->getLevel() == $b['combination']->getLevel()) {
                return $a['name'] > $b['name'] ? -1 : 1;
            }

            return ($a['combination']->getLevel() > $b['combination']->getLevel()) ? -1 : 1;
        });

        foreach ($gamers as $gamer) {
            echo $gamer['name'] . ' ' . $gamer['combination']->getName() . ' [' . implode(' ', array_map(function($card) {
                    return mb_strtoupper($card->getLevel()) . $card->getSuit();
                }, $gamer['combination']->getCombinationCard())) . '] [' . implode(' ', array_map(function($card) {
                    return mb_strtoupper($card->getLevel()) . $card->getSuit();
                }, $gamer['combination']->getUsesCards())). ']' . PHP_EOL;
        }
    }
}