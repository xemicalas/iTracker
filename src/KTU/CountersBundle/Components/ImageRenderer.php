<?php

namespace KTU\CountersBundle\Components;

/**
 * Class ImageRenderer, skirta piešti dinaminius paveiksliukus. Naudoja PHP GD biblioteką.
 * @package KTU\CountersBundle\Components
 * @author Marius Miškinis
 */
class ImageRenderer implements ImageRendererInterface
{
    /**
     * Paveiksliuko objektas
     * @var object
     */
    protected $image;

    /**
     * Nustatymų masyvas. Naudojamas paveiksliuko nustatymams saugoti
     * @var array
     */
    protected $options;

    /**
     * Paveiksliuko plotis
     * @var int
     */
    protected $width;

    /**
     * Paveiksliuko aukštis
     * @var int
     */
    protected $height;

    /**
     * Paveiksliuko buferis, į jį surašomas paveiksliuko bitų srautas (Bitmap)
     * @var array
     */
    protected $imageString;

    /**
     * @param $width int Image width
     * @param $height int Image height
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->createDefaultOptions();
        $this->createImage();
    }

    /**
     * Sukuria numatytasias reikšmes
     */
    private function createDefaultOptions()
    {
        $this->options = [
            'text_size' => 1
        ];
    }

    /**
     * Sukuria paveiksliuko objektą su nustatytu pločiu ir aukščiu
     */
    private function createImage()
    {
        $this->image = imagecreate($this->width, $this->height);
    }

    /**
     * Piešia grafika ant paveiksliuko. Priklausomai nuo pradžios ir pabaigos koordinačių, gali būti
     * piešiami pikseliai, linijos arba stačiakampiai
     * @param Color $color Spalvos objektas
     * @param $x int Pradžios X koordinatė
     * @param $y int Pradžios Y koordinatė
     * @param $endX int Pabaigos X koordinatė
     * @param $endY int Pabaigos Y koordinatė
     */
    private function draw(Color $color, $x, $y, $endX, $endY)
    {
        $cl = $this->createColor($color);
        imagefilledrectangle($this->image, $x, $y, $endX, $endY, $cl);
    }

    /**
     * Sunaikiną paveiksliuko objektą ir sukuria paveiksliuko buferį.
     * @return string Paveiksliuko buferis
     */
    private function flushImage()
    {
        ob_start();
        imagepng($this->image);
        $string = ob_get_clean();
        imagedestroy($this->image);
        return $string;
    }

    /**
     * Sukuria PHP GD spalvą priklausomai nuo Color klasės objekto
     * @param Color $color Color klasės objektas
     * @return object PHP GD spalvos objektas
     */
    protected function createColor(Color $color)
    {
        $cl = imagecolorallocate($this->image, $color->getR(), $color->getG(), $color->getB());
        return $cl;
    }

    /**
     * Sukuria permatomą PHP GD spalvą priklausomai nuo spalvos
     * @param $color object PHP GD spalva
     * @return object PHP GD spalvos objektas
     */
    protected function createTransparentColor($color)
    {
        $cl = imagecolortransparent($this->image, $color);
        return $cl;
    }

    /**
     * Piešia vertikaliai į paveiksliuką. Priklausomai nuo pradžios ir pabaigos koordinačių, gali būti
     * piešiami pikselis, linija arba stačiakampis
     * @param Color $color Color klasės objektas
     * @param $startX int X pradžios koordinatė
     * @param $endX int X pabaigos koordinatė
     * @param $y int Y koordinatė
     * @param $height int Linijos aukštis
     */
    protected function drawX(Color $color, $startX, $endX, $y, $height)
    {
        $this->draw($color, $startX, $y, $endX + ($height - 1), $y + ($height - 1));
    }

    /**
     * Piešia horizontaliai į paveiksliuką. Priklausomai nuo pradžios ir pabaigos koordinačių, gali būti
     * piešiami pikselis, linija arba stačiakampis
     * @param Color $color Color klasės objektas
     * @param $x int X koordinatė
     * @param $startY int Y pradžios koordinatė
     * @param $endY int Y pabaigos koordinatė
     * @param $width int Linijos plotis
     */
    protected function drawY(Color $color, $x, $startY, $endY, $width)
    {
        $this->draw($color, $x, $startY, $x + ($width - 1), $endY + ($width - 1));
    }

    /**
     * Piešia tekstą į paveiksliuką
     * @param Color $color Color klasės objektas
     * @param $text string Tekstas
     * @param $x int X koordinatė
     * @param $y int Y koordinatė
     */
    protected function drawString(Color $color, $text, $x, $y)
    {
        $cl = $this->createColor($color);
        imagestring($this->image, $this->options['text_size'], $x, $y, $text, $cl);
    }

    /**
     * Gražina paveiksliuko plotį
     * @return int Paveiksliuko plotis
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Gražina paveiksliuko aukštį
     * @return int Paveiksliuko aukštis
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Nustato parinkimą į nustatymų masyvą.
     * @param $option mixed Parinkimo indekso raktas
     * @param $value mixed Parinkimo norima reikšmė
     * @throws \OutOfBoundsException
     */
    public function setOption($option, $value)
    {
        if (!array_key_exists($option, $this->options)) {
            throw new \OutOfBoundsException();
        }
        $this->options[$option] = $value;
    }

    /**
     * Grąžina pasirinktą nustatymo reikšmę
     * @param $option mixed Pasirinkimo indekso raktas
     * @return mixed
     */
    public function getOption($option)
    {
        return $this->options[$option];
    }

    /**
     * Grąžina paveiksliuko buferį (bitmap)
     * @return mixed
     */
    public function getImageString()
    {
        return $this->imageString;
    }

    /**
     * Piešia paveiksliuką ir surašo jį į buferį
     */
    public function render()
    {
        $this->postRender();
        $this->imageString = $this->flushImage();
    }

    /**
     * Funkcija kurioje yra piešiama grafika
     * @return int
     */
    protected function postRender()
    {
        return 0;
    }
}