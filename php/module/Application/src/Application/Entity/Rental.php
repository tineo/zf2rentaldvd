<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rental
 *
 * @ORM\Table(name="rental", uniqueConstraints={@ORM\UniqueConstraint(name="rental_date", columns={"rental_date", "inventory_id", "customer_id"})}, indexes={@ORM\Index(name="idx_fk_inventory_id", columns={"inventory_id"}), @ORM\Index(name="idx_fk_customer_id", columns={"customer_id"}), @ORM\Index(name="idx_fk_staff_id", columns={"staff_id"})})
 * @ORM\Entity
 */
class Rental
{
    /**
     * @var integer
     *
     * @ORM\Column(name="rental_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rentalId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rental_date", type="datetime", nullable=false)
     */
    private $rentalDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="return_date", type="datetime", nullable=true)
     */
    private $returnDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update", type="datetime", nullable=false)
     */
    private $lastUpdate;

    /**
     * @var \Application\Entity\Staff
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Staff")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="staff_id", referencedColumnName="staff_id")
     * })
     */
    private $staff;

    /**
     * @var \Application\Entity\Inventory
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Inventory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inventory_id", referencedColumnName="inventory_id")
     * })
     */
    private $inventory;

    /**
     * @var \Application\Entity\Customer
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="customer_id")
     * })
     */
    private $customer;

    /** @ORM\PrePersist */
    function onPrePersist()
    {
        //using Doctrine DateTime here
        $this->lastUpdate = new \DateTime('now');
    }

    /**
     * Get rentalId
     *
     * @return integer 
     */
    public function getRentalId()
    {
        return $this->rentalId;
    }

    /**
     * Set rentalDate
     *
     * @param \DateTime $rentalDate
     * @return Rental
     */
    public function setRentalDate($rentalDate)
    {
        $this->rentalDate = $rentalDate;

        return $this;
    }

    /**
     * Get rentalDate
     *
     * @return \DateTime 
     */
    public function getRentalDate()
    {
        return $this->rentalDate;
    }

    /**
     * Set returnDate
     *
     * @param \DateTime $returnDate
     * @return Rental
     */
    public function setReturnDate($returnDate)
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    /**
     * Get returnDate
     *
     * @return \DateTime 
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     * @return Rental
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
     * Set staff
     *
     * @param \Application\Entity\Staff $staff
     * @return Rental
     */
    public function setStaff(\Application\Entity\Staff $staff = null)
    {
        $this->staff = $staff;

        return $this;
    }

    /**
     * Get staff
     *
     * @return \Application\Entity\Staff 
     */
    public function getStaff()
    {
        return $this->staff;
    }

    /**
     * Set inventory
     *
     * @param \Application\Entity\Inventory $inventory
     * @return Rental
     */
    public function setInventory(\Application\Entity\Inventory $inventory = null)
    {
        $this->inventory = $inventory;

        return $this;
    }

    /**
     * Get inventory
     *
     * @return \Application\Entity\Inventory 
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * Set customer
     *
     * @param \Application\Entity\Customer $customer
     * @return Rental
     */
    public function setCustomer(\Application\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Application\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}
