<?php

namespace KTU\CountersBundle\Components;

/**
 * Class ColorConverter, skirta dirbti su spalvos kodų konvertavimu iš vienos skaičiavimo sistemos į kitą
 * @package KTU\CountersBundle\Components
 * @author Marius Miškinis
 */
class ColorConverter
{
    /**
     * Konvertuoja šešioliktainį spalvos kodą (HEX) į dešimtainį RGB spalvos kodą
     * @param $hexColor
     * @return array
     */
    public static function toDecimals($hexColor)
    {
        $rHex = substr($hexColor, 1, 2);
        $gHex = substr($hexColor, 3, 2);
        $bHex = substr($hexColor, 5, 2);
        $r = hexdec($rHex);
        $g = hexdec($gHex);
        $b = hexdec($bHex);

        return array(
            'r' => $r,
            'g' => $g,
            'b' => $b
        );
    }

    /**
     * Konvertuoja šešioliktainį spalvos kodą (HEX) į Color objektą
     * @param $hexColor
     * @return Color
     */
    public static function toColor($hexColor)
    {
        $colors = self::toDecimals($hexColor);
        $color = new Color($colors['r'], $colors['g'], $colors['b']);

        return $color;
    }
}