<?php

namespace KTU\CountersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Counters
 *
 * @ORM\Table(name="counters", uniqueConstraints={@ORM\UniqueConstraint(name="url_UNIQUE", columns={"url"})}, indexes={@ORM\Index(name="fk_counters_users_idx", columns={"user_id"}), @ORM\Index(name="fk_counters_categories1_idx", columns={"cat_id"})})
 * @ORM\Entity
 */
class Counters
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=45, nullable=false)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="counterDesc", type="text", nullable=true)
     */
    protected $counterdesc;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \KTU\CountersBundle\Entity\Categories
     *
     * @ORM\ManyToOne(targetEntity="KTU\CountersBundle\Entity\Categories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cat_id", referencedColumnName="id")
     * })
     */
    protected $cat;

    /**
     * @var \KTU\CountersBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="KTU\CountersBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    protected $user_id;

    /**
     * @var string
     *
     * @ORM\Column(name="backgroundColor", type="string", length=7, nullable=false)
     */
    protected $backgroundColor;

    /**
     * @var string
     *
     * @ORM\Column(name="borderColor", type="string", length=7, nullable=false)
     */
    protected $borderColor;

    /**
     * @var string
     *
     * @ORM\Column(name="textColor", type="string", length=7, nullable=false)
     */
    protected $textColor;

    /**
     * @var string
     *
     * @ORM\Column(name="uniqueColor", type="string", length=7, nullable=false)
     */
    protected $uniqueColor;

    /**
     * @var string
     *
     * @ORM\Column(name="totalColor", type="string", length=7, nullable=false)
     */
    protected $totalColor;

    /**
     * @var string
     *
     * @ORM\Column(name="barTotalColor", type="string", length=7, nullable=false)
     */
    protected $barTotalColor;

    /**
     * @var string
     *
     * @ORM\Column(name="barUniqueColor", type="string", length=7, nullable=false)
     */
    protected $barUniqueColor;

    /**
     * @var boolean
     * @ORM\Column(name="transparentBackground", type="boolean", nullable=true)
     */
    protected $transparentBackground;

    /**
     * @var collection
     * @ORM\OneToMany(targetEntity="KTU\CountersBundle\Entity\CounterStatistics", mappedBy="counter_id")
     */
    protected $statistics;

    public function __construct()
    {
        $this->statistics = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Counters
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Counters
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set counterdesc
     *
     * @param string $counterdesc
     * @return Counters
     */
    public function setCounterdesc($counterdesc)
    {
        $this->counterdesc = $counterdesc;

        return $this;
    }

    /**
     * Get counterdesc
     *
     * @return string 
     */
    public function getCounterdesc()
    {
        return $this->counterdesc;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cat
     *
     * @param \KTU\CountersBundle\Entity\Categories $cat
     * @return Counters
     */
    public function setCat($cat = null)
    {
        $this->cat = $cat;

        return $this;
    }

    /**
     * Get cat
     *
     * @return \KTU\CountersBundle\Entity\Categories 
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Set user
     *
     * @param \KTU\CountersBundle\Entity\Users $user
     * @return Counters
     */
    public function setUserId(\KTU\CountersBundle\Entity\Users $user = null)
    {
        $this->user_id = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \KTU\CountersBundle\Entity\Users 
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set background color
     * @param $color
     * @return $this
     */
    public function setBackgroundColor($color)
    {
        $this->backgroundColor = $color;

        return $this;
    }

    /**
     * Get background color
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * Set border color
     * @param $color
     * @return $this
     */
    public function setBorderColor($color)
    {
        $this->borderColor = $color;

        return $this;
    }

    /**
     * Get border color
     * @return string
     */
    public function getBorderColor()
    {
        return $this->borderColor;
    }

    /**
     * Set text color
     * @param $color
     * @return $this
     */
    public function setTextColor($color)
    {
        $this->textColor = $color;

        return $this;
    }

    /**
     * Get text color
     * @return string
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * Set unique color
     * @param $color
     * @return $this
     */
    public function setUniqueColor($color)
    {
        $this->uniqueColor = $color;

        return $this;
    }

    /**
     * Get total color
     * @return string
     */
    public function getUniqueColor()
    {
        return $this->uniqueColor;
    }

    /**
     * Set total color
     * @param $color
     * @return $this
     */
    public function setTotalColor($color)
    {
        $this->totalColor = $color;

        return $this;
    }

    /**
     * Get unique color
     * @return string
     */
    public function getTotalColor()
    {
        return $this->totalColor;
    }

    /**
     * Set barTotal color
     * @param $color
     * @return $this
     */
    public function setBarTotalColor($color)
    {
        $this->barTotalColor = $color;

        return $this;
    }

    /**
     * Get barTotal color
     * @return string
     */
    public function getBarTotalColor()
    {
        return $this->barTotalColor;
    }

    /**
     * Set barUnique color
     * @param $color
     * @return $this
     */
    public function setBarUniqueColor($color)
    {
        $this->barUniqueColor = $color;

        return $this;
    }

    /**
     * Get barUnique color
     * @return string
     */
    public function getBarUniqueColor()
    {
        return $this->barUniqueColor;
    }

    /**
     * Set transparent background
     * @param $isTransparent
     */
    public function setTransparentBackground($isTransparent)
    {
        $this->transparentBackground = $isTransparent;
    }

    /**
     * Get transparent background
     * @return bool
     */
    public function getTransparentBackground()
    {
        return $this->transparentBackground;
    }

    /**
     * Set statistics
     * @param ArrayCollection $statistics
     */
    public function setStatistics(ArrayCollection $statistics)
    {
        $this->statistics = $statistics;
    }

    /**
     * Get statistics
     * @return ArrayCollection|collection
     */
    public function getStatistics()
    {
        return $this->statistics;
    }
}
