<?php

namespace KTU\CountersBundle\Components;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Locale provides functionality setting languages
 * @package KTU\CountersBundle\Components
 * @author Marius Miškinis
 */
class Locale
{
    /**
     * Request object
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * Current locale
     * @var string
     */
    private $locale;

    /**
     * @param Request $request Request object
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->locale = null;
    }

    /**
     * Gets default user's locale according to the user's GEO location
     * @param $languages array Languages array
     * @return mixed Returns locale if locale has been set to the user's country language
     */
    private function getGeoLocale($languages)
    {
        $locator = new GeoLocator($this->request->getClientIp());
        $locale = strtolower($locator->getCountryCode());
        $locale = $this->analyze($locale);
        if (in_array($locale, $languages)) {
            return $locale;
        }
        return null;
    }

    /**
     * Writes locale to cookies
     */
    private function writeCookies($locale)
    {
        $response = new Response();
        $response->headers->setCookie(new Cookie('locale', $locale));
        $response->send();
    }

    /**
     * Analyzes and corrects the locale code
     * @param $locale string Locale
     * @return string
     */
    private function analyze($locale) {
        switch ($locale) {
            case 'lt':
                return 'lt_LT';
            default:
                return $locale;
        }
    }

    /**
     * Sets locale and writes locale to cookies
     * @param $locale
     */
    public function setCookieLocale($locale)
    {
        $this->writeCookies($locale);
        $this->setLocale($locale);
    }

    /**
     * Sets the locale
     * @param $locale
     */
    public function setLocale($locale) {
        $this->locale = $locale;
        $this->request->setLocale($this->locale);
    }

    /**
     * Gets locale from cookies data
     * @return mixed
     */
    public function getCookieLocale() {
        return $this->request->cookies->get('locale');
    }

    /**
     * Sets current locale according to cookies data, if cookies are empty, gets the user's GEO locale
     */
    public function setGlobLocale($languages)
    {
        if (($locale = $this->getCookieLocale()) != null) {
            $this->setLocale($locale);
        } else {
            $locale = $this->getGeoLocale($languages);
            if ($locale != null) {
                $this->setCookieLocale($locale);
            }
        }
    }
}