<?php

namespace KTU\CountersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CounterStatistics
 *
 * @ORM\Table(name="counter_statistics", indexes={@ORM\Index(name="fk_counter_statistics_counters1", columns={"counter_id"})})
 * @ORM\Entity
 */
class CounterStatistics
{
    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15, nullable=false)
     */
    protected $ip;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \KTU\CountersBundle\Entity\Counters
     *
     * @ORM\ManyToOne(targetEntity="KTU\CountersBundle\Entity\Counters")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="counter_id", referencedColumnName="id")
     * })
     */
    protected $counter_id;

    /**
     * @var date
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    protected $date;

    /**
     * Set ip
     *
     * @param string $ip
     * @return CounterStatistics
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
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
     * Set counter
     *
     * @param \KTU\CountersBundle\Entity\Counters $counter
     * @return CounterStatistics
     */
    public function setCounter(\KTU\CountersBundle\Entity\Counters $counter = null)
    {
        $this->counter_id = $counter;

        return $this;
    }

    /**
     * Get counter
     *
     * @return \KTU\CountersBundle\Entity\Counters 
     */
    public function getCounter()
    {
        return $this->counter_id;
    }

    /**
     * Set date
     *
     * @param date $date
     * @return CounterStatistics
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \KTU\CountersBundle\Entity\Counters
     */
    public function getDate()
    {
        return $this->date;
    }
}
