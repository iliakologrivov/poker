<?php

namespace App\Cards;

class Card {

    protected $alias;

    protected $value;

    protected $suit;

    protected $level;

    public function __construct(string $alias, string $suit, string $level)
    {
        $this->alias = $alias;
        $this->suit = $suit;
        $this->level = $level;

        switch ($this->getLevel()) {
            case 'j':
                $this->value = 11;
                break;
            case 'q':
                $this->value = 12;
                break;
            case 'k':
                $this->value = 13;
                break;
            case 'a':
                $this->value = 14;
                break;
            default:
                $this->value = (int) $this->getLevel();
        }

        return $this;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getSuit()
    {
        return $this->suit;
    }

    public function getLevel()
    {
        return $this->level;
    }
}