<?php

namespace KTU\CountersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Categories
 *
 * @ORM\Table(name="categories", uniqueConstraints={@ORM\UniqueConstraint(name="category_UNIQUE", columns={"category"})})
 * @ORM\Entity
 */
class Categories
{
    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=false)
     */
    protected $category;

    /**
     * @var string
     *
     * @ORM\Column(name="category_en", type="string", length=255, nullable=false)
     */
    protected $categoryEn;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var collection
     * @ORM\OneToMany(targetEntity="KTU\CountersBundle\Entity\Counters", mappedBy="cat")
     */
    protected $counters;

    public function __construct()
    {
        $this->counters = new ArrayCollection();
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Categories
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set categoryEn
     *
     * @param string $categoryEn
     * @return Categories
     */
    public function setCategoryEn($categoryEn)
    {
        $this->categoryEn = $categoryEn;

        return $this;
    }

    /**
     * Get categoryEn
     *
     * @return string
     */
    public function getCategoryEn()
    {
        return $this->categoryEn;
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

    public function __toString()
    {
        return $this->category;
    }
}
