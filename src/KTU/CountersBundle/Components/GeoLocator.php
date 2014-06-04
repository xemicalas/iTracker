<?php

namespace KTU\CountersBundle\Components;

/**
 * Class GeoLocator provides geographic information about IP
 * @package KTU\CountersBundle\Components
 * @author Marius Miškinis
 */
class GeoLocator
{
    /**
     * URL of the GEO information provider
     */
    var $SERVICE_URL = 'http://www.geoplugin.net/json.gp?ip=%s';

    /**
     * Null parameter's value
     */
    var $NULL_VALUE = 'unknown';

    /**
     * Geographic information's array
     * @var array
     */
    private $geoObj;

    /**
     * Internet protocol IPv4
     * @var string
     */
    private $ip;

    /**
     * @param $ip string Internet protocol IPv4
     */
    public function __construct($ip)
    {
        $this->ip = $ip;
        $this->geoObj = $this->locate();
    }

    /**
     * Gets all geographic information about IP
     * @return array
     */
    private function locate()
    {
        $geo = @json_decode(@file_get_contents(sprintf($this->SERVICE_URL, $this->ip)));
        return $geo;
    }

    /**
     * Gets IP request
     * @return string
     */
    public function getRequest()
    {
        return $this->geoObj->geoplugin_request != null ?
            $this->geoObj->geoplugin_request : $this->NULL_VALUE;
    }

    /**
     * Gets GEO status, if 200 then succeeded
     * @return string
     */
    public function getStatus()
    {
        return $this->geoObj->geoplugin_status != null ?
            $this->geoObj->geoplugin_status : $this->NULL_VALUE;
    }

    /**
     * Gets city name
     * @return string
     */
    public function getCity()
    {
        return $this->geoObj->geoplugin_city != null ?
            $this->geoObj->geoplugin_city : $this->NULL_VALUE;
    }

    /**
     * Gets region
     * @return string
     */
    public function getRegion()
    {
        return $this->geoObj->geoplugin_region != null ?
            $this->geoObj->geoplugin_region : $this->NULL_VALUE;
    }

    /**
     * Gets area code
     * @return string
     */
    public function getAreaCode()
    {
        return $this->geoObj->geoplugin_areaCode != null ?
            $this->geoObj->geoplugin_areaCode : $this->NULL_VALUE;
    }

    /**
     * Gets DMA code
     * @return string
     */
    public function getDmaCode()
    {
        return $this->geoObj->geoplugin_dmaCode != null ?
            $this->geoObj->geoplugin_dmaCode : $this->NULL_VALUE;
    }

    /**
     * Gets country code
     * @return string
     */
    public function getCountryCode()
    {
        return $this->geoObj->geoplugin_countryCode != null ?
            $this->geoObj->geoplugin_countryCode : $this->NULL_VALUE;
    }

    /**
     * Gets country name
     * @return string
     */
    public function getCountryName()
    {
        return $this->geoObj->geoplugin_countryName != null ?
            $this->geoObj->geoplugin_countryName : $this->NULL_VALUE;
    }

    /**
     * Gets continent code
     * @return string
     */
    public function getContinentCode()
    {
        return $this->geoObj->geoplugin_continentCode != null ?
            $this->geoObj->geoplugin_continentCode : $this->NULL_VALUE;
    }

    /**
     * Gets geographic latitude
     * @return string
     */
    public function getLatitude()
    {
        return $this->geoObj->geoplugin_latitude != null ?
            $this->geoObj->geoplugin_latitude : $this->NULL_VALUE;
    }

    /**
     * Gets geographic longitude
     * @return string
     */
    public function getLongitude()
    {
        return $this->geoObj->geoplugin_longitude != null ?
            $this->geoObj->geoplugin_longitude : $this->NULL_VALUE;
    }

    /**
     * Gets region code
     * @return string
     */
    public function getRegionCode()
    {
        return $this->geoObj->geoplugin_regionCode != null ?
            $this->geoObj->geoplugin_regionCode : $this->NULL_VALUE;
    }

    /**
     * Gets region name
     * @return string
     */
    public function getRegionName()
    {
        return $this->geoObj->geoplugin_regionName != null ?
            $this->geoObj->geoplugin_regionName : $this->NULL_VALUE;
    }

    /**
     * Gets currency code
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->geoObj->geoplugin_currencyCode != null ?
            $this->geoObj->geoplugin_currencyCode : $this->NULL_VALUE;
    }

    /**
     * Gets currency symbol
     * @return string
     */
    public function getCurrencySymbol()
    {
        return $this->geoObj->geoplugin_currencySymbol != null ?
            $this->geoObj->geoplugin_currencySymbol : $this->NULL_VALUE;
    }

    /**
     * Changes default parameter's null value
     * @param $val mixed Preferred null value
     */
    public function setNullValue($val)
    {
        $this->NULL_VALUE = $val;
    }

    /**
     * Gets parameter's null value
     * @return string
     */
    public function getNullValue()
    {
        return $this->NULL_VALUE;
    }
}