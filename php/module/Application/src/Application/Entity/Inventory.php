<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inventory
 *
 * @ORM\Table(name="inventory", indexes={@ORM\Index(name="idx_fk_film_id", columns={"film_id"}), @ORM\Index(name="idx_store_id_film_id", columns={"store_id", "film_id"}), @ORM\Index(name="fk_inventory_store_idx", columns={"store_id"})})
 * @ORM\Entity
 */
class Inventory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="inventory_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $inventoryId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update", type="datetime", nullable=false)
     */
    private $lastUpdate;

    /**
     * @var \Application\Entity\Store
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Store")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="store_id", referencedColumnName="store_id")
     * })
     */
    private $store;

    /**
     * @var \Application\Entity\Film
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Film")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="film_id", referencedColumnName="film_id")
     * })
     */
    private $film;

    /** @ORM\PrePersist */
    function onPrePersist()
    {
        //using Doctrine DateTime here
        $this->lastUpdate = new \DateTime('now');
    }

    /**
     * Get inventoryId
     *
     * @return integer 
     */
    public function getInventoryId()
    {
        return $this->inventoryId;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     * @return Inventory
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime 
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Set store
     *
     * @param \Application\Entity\Store $store
     * @return Inventory
     */
    public function setStore(\Application\Entity\Store $store = null)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * Get store
     *
     * @return \Application\Entity\Store 
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Set film
     *
     * @param \Application\Entity\Film $film
     * @return Inventory
     */
    public function setFilm(\Application\Entity\Film $film = null)
    {
        $this->film = $film;

        return $this;
    }

    /**
     * Get film
     *
     * @return \Application\Entity\Film 
     */
    public function getFilm()
    {
        return $this->film;
    }
}
