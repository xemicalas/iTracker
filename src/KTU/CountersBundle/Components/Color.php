<?php

namespace KTU\CountersBundle\Components;

/**
 * Class Color, skirta dirbti su RGB spalvomis
 * @package KTU\CountersBundle\Components
 * @author Marius Miškinis
 */
class Color
{
    /**
     * Atitinka raudonos spalvos kodą
     * @var integer
     */
    private $r;

    /**
     * Atitinka geltonos spalvos kodą
     * @var integer
     */
    private $g;

    /**
     * Atitinka mėlynos spalvos kodą
     * @var integer
     */
    private $b;

    public function __construct($r, $g, $b)
    {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    /**
     * Nustato raudonos spalvos reikšmę
     * @param $r
     */
    public function setR($r)
    {
        $this->r = $r;
    }

    /**
     * Nustato geltonos spalvos reikšmę
     * @param $g
     */
    public function setG($g)
    {
        $this->g = $g;
    }

    /**
     * Nustato mėlynos spalvos reikšmę
     * @param $b
     */
    public function setB($b)
    {
        $this->b = $b;
    }

    /**
     * Grąžina raudonos spalvos reikšmę
     * @return int
     */
    public function getR()
    {
        return $this->r;
    }

    /**
     * Grąžina geltonos spalvos reikšmę
     * @return int
     */
    public function getG()
    {
        return $this->g;
    }

    /**
     * Grąžina mėlynos spalvos reikšmę
     * @return int
     */
    public function getB()
    {
        return $this->b;
    }

    /**
     * Klasės objektą konvertuoja į masyvą
     * @return array
     */
    public function toArray()
    {
        return array(
            'r' => $this->r,
            'g' => $this->g,
            'b' => $this->b
        );
    }

}