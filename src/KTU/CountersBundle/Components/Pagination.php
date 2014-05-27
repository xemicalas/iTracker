<?php

namespace KTU\CountersBundle\Components;

/**
 * Pagination is the class which generates pages according to amount of records.
 * @package KTU\CountersBundle\Components
 * @author Marius Miškinis
 */
class Pagination
{
    /**
     * Defines current page.
     * @var integer
     */
    private $currentPage;

    /**
     * Defines total number of records.
     * @var integer
     */
    private $records;

    /**
     * Defines number of records in one page.
     * @var integer
     */
    private $recordsInPage;

    /**
     * Url to page.
     * @var string
     */
    private $url;

    /**
     * Stores HTML blocks.
     * @var array
     */
    private $buffer;

    public function __construct($currentPage, $records, $recordsInPage, $url)
    {
        $this->currentPage = (int)$currentPage;
        $this->records = (int)$records;
        $this->recordsInPage = (int)$recordsInPage;
        $this->url = $url;
        $this->buffer = [];
    }

    /**
     * Appends string to data array
     * @param $string
     */
    private function appendData($string)
    {
        $this->buffer[] = $string;
    }

    /**
     * Generates navigation left button
     * @param $page integer Current page
     * @param bool $isDisabled
     */
    private function generateLeftButton($page, $isDisabled = false)
    {
        if (!$isDisabled) {
            $this->appendData(sprintf('<li><a href="%s%d">&laquo;</a></li>', $this->url, $page - 1));
        } else {
            $this->appendData(sprintf('<li class="disabled"><a href="#">&laquo;</a></li>', $this->url, $page - 1));
        }
    }

    /**
     * Generates navigation right button
     * @param $page
     * @param bool $isDisabled
     */
    private function generateRightButton($page, $isDisabled = false)
    {
        if (!$isDisabled) {
            $this->appendData(sprintf('<li><a href="%s%d">&raquo;</a></li>', $this->url, $page + 1));
        } else {
            $this->appendData(sprintf('<li class="disabled"><a href="#">&raquo;</a></li>', $this->url, $page + 1));
        }
    }

    /**
     * Get pagination's shift depending on current page.
     * @return int
     */
    private function getShift()
    {
        $all_pages = self::getPages($this->records, $this->recordsInPage);
        if ($this->currentPage < 3 || $this->currentPage >= $all_pages - 1) {
            $shift = 3;
        } else {
            $shift = 1;
        }
        return $shift;
    }

    /**
     * Get pagination's left shift depending on current page.
     * @return int
     */
    private function getLeftShift()
    {
        if ($this->currentPage < 3) {
            $shift = 3;
        } else {
            $shift = 1;
        }
        return $shift;
    }

    /**
     * Get pagination's right shift depending on current page.
     * @return int
     */
    private function getRightShift()
    {
        $all_pages = self::getPages($this->records, $this->recordsInPage);
        if ($this->currentPage >= $all_pages - 1) {
            $shift = 3;
        } else {
            $shift = 1;
        }
        return $shift;
    }

    /*
     * Generates HTML pages.
     */
    private function generatePages()
    {
        $all_pages = self::getPages($this->records, $this->recordsInPage);
        $total = 0;
        $start = 1;
        while ($total < $all_pages) {
            $shift = $this->getShift();
            $paging_start = $total < $this->getLeftShift() ? true : false;
            $paging_end =  $total >= ($all_pages - $this->getRightShift()) ? true : false;

            if ($this->currentPage == $start) {
                $this->appendData(sprintf('<li class="active"><span>%d</span></li>', $start));
            }
            elseif ($paging_start|| $paging_end) {
                $this->appendData(sprintf('<li><a href="%s%d">%d</a></li>', $this->url, $start, $start));
            }
            elseif (($this->currentPage == $start - $shift) || ($this->currentPage == $start + $shift)) {
                $this->appendData('<li><span>...</span></li>');
            }
            $total++;
            $start++;
        }
    }

    /**
     * Generates overall HTML block
     */
    public function generatePagination()
    {
        $this->buffer = [];

        if ($this->currentPage != 1) {
            $this->generateLeftButton($this->currentPage);
        } else {
            $this->generateLeftButton($this->currentPage, true);
        }

        $this->generatePages();

        if ($this->currentPage != self::getPages($this->records, $this->recordsInPage)) {
            $this->generateRightButton($this->currentPage);
        } else {
            $this->generateLeftButton($this->currentPage, true);
        }
    }

    /**
     * Sets current page
     * @param $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * Sets total amount of records
     * @param $records
     */
    public function setRecords($records)
    {
        $this->records = $records;
    }

    /**
     * Sets number of records in one page.
     * @param $recordsInPage
     */
    public function setRecordsInPage($recordsInPage)
    {
        $this->recordsInPage = $recordsInPage;
    }

    /**
     * Sets current url.
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Gets maximum possible number of pages.
     * @param $records integer Total amount of records
     * @param $recordsInPage integer Number of records displayed in one page
     * @return integer
     */
    public static function getPages($records, $recordsInPage) {
        $all_pages = ceil($records / $recordsInPage);
        return $all_pages;
    }

    /**
     * Gets pagination depending on records when extracting data from database
     * @param $currentPage int Current page
     * @param $pageSize int Number of records in each page
     * @return int
     */
    public static function getRecordPagination($currentPage, $pageSize)
    {
        return ($pageSize * $currentPage) - $pageSize;
    }

    /**
     * Builds string from data array.
     * @return string
     */
    public function buildString()
    {
        $string = '';
        foreach ($this->buffer as $str) {
            $string .= $str."\n";
        }
        return $string;
    }

    public function __toString()
    {
        return $this->buildString();
    }
}