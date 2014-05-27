<?php

namespace KTU\CountersBundle\Components;

/**
 * Interface ImageRendererInterface. Paveiksliuko interfeisas, skirtas piešti dinaminius paveiksliukus.
 * @package KTU\CountersBundle\Components
 * @author Marius Miškinis
 */
interface ImageRendererInterface
{
    /**
     * Grąžina paveiksliuko plotį
     * @return int Paveiksliuko plotis
     */
    public function getWidth();

    /**
     * Grąžina paveiksliuko aukštį
     * @return int Paveiksliuko aukštis
     */
    public function getHeight();

    /**
     * Grąžina paveiksliuko buferį (bitmap)
     * @return mixed
     */
    public function getImageString();

    /**
     * Piešia paveiksliuką ir surašo jį į buferį
     */
    public function render();
}