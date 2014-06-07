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
     * @ORM\Column(name="category_lt", type="string", length=255, nullable=false)
     */
    protected $categoryLt;

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
     * Set categoryLt
     *
     * @param string $categoryLt
     * @return Categories
     */
    public function setCategoryLt($categoryLt)
    {
        $this->categoryLt = $categoryLt;

        return $this;
    }

    /**
     * Get categoryLt
     *
     * @return string
     */
    public function getCategoryLt()
    {
        return $this->categoryLt;
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
