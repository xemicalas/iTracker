<?php

namespace KTU\CountersBundle\Components;

/**
 * Class CounterImageRenderer, kuri atvaizduoja statistinį paveiklėlį (skaitliuką).
 * @package KTU\CountersBundle\Components
 * @author Marius Miškinis
 */
class CounterImageRenderer extends ImageRenderer
{
    /**
     * Skaitliuko trumpas aprašymas, kuris bus atvaizduojamas paveiklėlyje
     * @var string
     */
    private $counterText;

    /**
     * Pilnutinis skaičius, gali būti pilnutinis unikalių lankytojų skaičius arba
     * pilnutinis visų paspaudimų skaičius.
     * @var string
     */
    private $totalNumber;

    /**
     * Šiandieninis skaičius, gali būti šiandieninis unikalių lankytojų skaičius arba
     * šiandieninis visų paspaudimų skaičius.
     * @var string
     */
    private $todayNumber;

    /**
     * Masyvas kuriame talpinami yra statistikos duomenys, pagal jį paveiklėlyje yra piešiama statistikos diagrama
     * @var array
     */
    private $statistics;

    public function __construct($width, $height, $todayNumber, $totalNumber)
    {
        parent::__construct($width, $height);
        $this->todayNumber = sprintf('+%d', $todayNumber);
        $this->totalNumber = $totalNumber;
        $this->statistics = array();
        $this->appendDefaultOptions();
    }

    /**
     * Papildo nustatymus iš ImageRenderer klasės
     */
    private function appendDefaultOptions()
    {
        $this->options['base_text_size'] = 0;
        $this->options['unique_padding_left'] = 5;
        $this->options['total_unique_padding_left'] = 5;
        $this->options['counter_text_padding_left'] = 6;
        $this->options['stats_start_x'] = 40;
        $this->options['stats_start_y'] = 20;
        $this->options['stats_max_height'] = 18;
        $this->options['background_color'] = new Color(240, 240, 240);
        $this->options['transparent_background'] = false;
        $this->options['text_color'] = new Color(163, 163, 163);
        $this->options['unique_color'] = new Color(163, 163, 163);
        $this->options['total_color'] = new Color(163, 163, 163);
        $this->options['border_width'] = 1;
        $this->options['border_color'] = new Color(163, 163, 163);
        $this->options['draw_border'] = true;
        $this->options['bar_total_color'] = new Color(163, 163, 163);
        $this->options['bar_unique_color'] = new Color(99, 99, 99);
    }

    /**
     * Nustato paveikslėlio fono spalvą
     */
    private function allocateBackgroundColor()
    {
        $bg = $this->options['background_color'];
        $color = $this->createColor($bg);
        if ($this->options['transparent_background']) {
            $this->createTransparentColor($color);
        }
    }

    /**
     * Grąžina Y koordinates priklausomai nuo teksto šrifto dydžio
     * @param $startY int Pradedančioji Y koordinatė.
     * @return int
     */
    private function getTextY($startY)
    {
        return $startY - (8 + ($this->options['text_size'] * 2));
    }

    /**
     * Piešiamas skaitliuko aprašymas į paveikslėlį
     */
    private function drawCounterText()
    {
        $this->drawString(
            $this->options['text_color'],
            $this->counterText,
            $this->options['counter_text_padding_left'],
            $this->getTextY($this->height));
    }

    /**
     * Piešiamas šiandienos unikalus skaičius į paveiklėlį
     */
    private function drawUniqueText()
    {
        $this->drawString(
            $this->options['unique_color'],
            $this->todayNumber, $this->options['unique_padding_left'], $this->getTextY(22));
    }

    /**
     * Piešiamas pilnutinis skaičius į paveiklėlį
     */
    private function drawTotalUniqueText()
    {
        $this->drawString(
            $this->options['total_color'],
            $this->totalNumber,
            $this->options['total_unique_padding_left'],
            $this->getTextY(12));
    }

    /**
     * Piešiami kraštai (rėmai) į paveiklėlį
     */
    private function drawBorder()
    {
        $border_color = $this->options['border_color'];
        $this->drawX($border_color, 0, $this->width, 0, $this->options['border_width']);
        $this->drawX($border_color, 0, $this->width, $this->height - 1, $this->options['border_width']);
        $this->drawY($border_color, 0, $this->height, 0, $this->options['border_width']);
        $this->drawY($border_color, $this->width - 1, $this->height, 0, $this->options['border_width']);
    }

    /**
     * Grąžina statistinės diagramos aukštį, priklausomai nuo pilnutinio ir dabartinio skaičiaus.
     * Atliekama proporciškai.
     * @param $total int Pilnutinis skaičius
     * @param $num int Dabartinis skaičius
     * @return int
     */
    private function getBarHeight($total, $num)
    {
        $barHeightPercent = (($num * 100) / $total);
        $barHeight = ceil(($this->options['stats_max_height'] * $barHeightPercent) / 100);

        return $barHeight;
    }

    /**
     * Grąžina maksimalų statistinės diagramos aukštį, priklausomai nuo statistinių duomenų
     * @return int
     */
    private function getMaxTotal()
    {
        $maxTotal = 0;
        foreach ($this->statistics as $stat) {
            if ($stat['total'] > $maxTotal) {
                $maxTotal = $stat['total'];
            }
        }
        return $maxTotal;
    }

    /**
     * Į statistikos masyvą, kiekvienai diagramai įrašo jos aukštį
     */
    private function setStatsHeight()
    {
        $maxTotal = $this->getMaxTotal();
        foreach ($this->statistics as &$stat) {
            $stat['bar_total_height'] = $this->getBarHeight($maxTotal, $stat['total']);
            $stat['bar_unique_height'] = $this->getBarHeight($maxTotal, $stat['uniq']);
        }
    }

    /**
     * Piešiamos statistinės diagramos
     */
    private function drawStatisticsBars()
    {
        $this->setStatsHeight();
        $shift = 0;
        foreach ($this->statistics as $stats) {
            $this->drawY($this->options['bar_total_color'], $this->options['stats_start_x'] + $shift,
                $this->options['stats_start_y'], $this->options['stats_start_y'] - $stats['bar_total_height'], 2);

            $this->drawY($this->options['bar_unique_color'], $this->options['stats_start_x'] + $shift,
                $this->options['stats_start_y'], $this->options['stats_start_y'] - $stats['bar_unique_height'], 2);
            $shift += 3;
        }
    }

    /**
     * Paveldėtas metodas iš ImageRender klasės. Piešiamas statistikos paveiksliukas (skaitliukas)
     * @return int|void
     */
    protected function postRender()
    {
        $this->allocateBackgroundColor();
        if ($this->options['draw_border']) {
            $this->drawBorder();
        }
        if (count($this->statistics) > 0) {
            $this->drawStatisticsBars();
        }
        if ($this->counterText != null) {
            $this->drawCounterText();
        }
        $this->options['text_size'] = 2 + $this->options['base_text_size'];
        $this->drawUniqueText();
        $this->drawTotalUniqueText();
    }

    /**
     * Grąžina šiandieninį skaičių
     * @param $todayNumber int Šiandieninis skaičius
     */
    public function setTodayNumber($todayNumber)
    {
        $this->todayNumber = sprintf('+%d', $todayNumber);
    }

    /**
     * Nustato pilnutinį skaičių
     * @param $total int Pilnutinis skaičius
     */
    public function setTotalNumber($total)
    {
        $this->totalNumber = $total;
    }

    /**
     * Nustato skaitliuko aprašymo tekstą
     * @param $text string Skaitiuko aprašymas
     */
    public function setCounterText($text)
    {
        $this->counterText = $text;
    }

    /**
     * Nustato statistikos diagramų masyvą
     * @param array $stats Statistikos masyvas
     */
    public function setStatistics(array $stats)
    {
        $this->statistics = $stats;
    }

    /**
     * Grąžina šiandieninį skaičių
     * @return string Šiandieninis skaičius
     */
    public function getTodayText()
    {
        return $this->todayNumber;
    }


    /**
     * Grąžina pilnutini skaičių
     * @return string Pilnutinis skaičius
     */
    public function getTotalText()
    {
        return $this->totalNumber;
    }

    /**
     * Grąžina skaitliuko aprašymą
     * @return string Skaitliuko aprašymas
     */
    public function getCounterText()
    {
        return $this->counterText;
    }

    /**
     * Nustato ar bus piešiami skaitliuko rėmai
     * @param $drawBorder bool Ar piešti rėmus
     */
    public function setDrawBorder($drawBorder)
    {
        $this->options['draw_border'] = $drawBorder;
    }
}