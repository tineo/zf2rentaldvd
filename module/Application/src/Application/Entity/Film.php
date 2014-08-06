<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Film
 *
 * @ORM\Table(name="film", indexes={@ORM\Index(name="idx_title", columns={"title"}), @ORM\Index(name="idx_fk_language_id", columns={"language_id"}), @ORM\Index(name="idx_fk_original_language_id", columns={"original_language_id"})})
 * @ORM\Entity
 */
class Film
{
    /**
     * @var integer
     *
     * @ORM\Column(name="film_id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $filmId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="release_year", type="integer", nullable=true)
     */
    private $releaseYear;

    /**
     * @var integer
     *
     * @ORM\Column(name="rental_duration", type="integer", nullable=false)
     */
    private $rentalDuration = '3';

    /**
     * @var string
     *
     * @ORM\Column(name="rental_rate", type="decimal", precision=4, scale=2, nullable=false)
     */
    private $rentalRate = '4.99';

    /**
     * @var integer
     *
     * @ORM\Column(name="length", type="smallint", nullable=true)
     */
    private $length;

    /**
     * @var string
     *
     * @ORM\Column(name="replacement_cost", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $replacementCost = '19.99';

    /**
     * @var string
     *
     * @ORM\Column(name="rating", type="string", nullable=true)
     */
    private $rating = 'G';

    /**
     * @var string
     *
     * @ORM\Column(name="special_features", type="string", nullable=true)
     */
    private $specialFeatures;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update", type="datetime", nullable=false)
     */
    private $lastUpdate;

    /**
     * @var \Application\Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="language_id")
     * })
     */
    private $language;

    /**
     * @var \Application\Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="original_language_id", referencedColumnName="language_id")
     * })
     */
    private $originalLanguage;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Actor", mappedBy="film")
     */
    private $actor;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Category", inversedBy="film")
     * @ORM\JoinTable(name="film_category",
     *   joinColumns={
     *     @ORM\JoinColumn(name="film_id", referencedColumnName="film_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="category_id", referencedColumnName="category_id")
     *   }
     * )
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actor = new \Doctrine\Common\Collections\ArrayCollection();
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /** @ORM\PrePersist */
    function onPrePersist()
    {
        //using Doctrine DateTime here
        $this->lastUpdate = new \DateTime('now');
    }


    /**
     * Get filmId
     *
     * @return integer 
     */
    public function getFilmId()
    {
        return $this->filmId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Film
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Film
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set releaseYear
     *
     * @param integer $releaseYear
     * @return Film
     */
    public function setReleaseYear($releaseYear)
    {
        $this->releaseYear = $releaseYear;

        return $this;
    }

    /**
     * Get releaseYear
     *
     * @return integer 
     */
    public function getReleaseYear()
    {
        return $this->releaseYear;
    }

    /**
     * Set rentalDuration
     *
     * @param boolean $rentalDuration
     * @return Film
     */
    public function setRentalDuration($rentalDuration)
    {
        $this->rentalDuration = $rentalDuration;

        return $this;
    }

    /**
     * Get rentalDuration
     *
     * @return boolean 
     */
    public function getRentalDuration()
    {
        return $this->rentalDuration;
    }

    /**
     * Set rentalRate
     *
     * @param string $rentalRate
     * @return Film
     */
    public function setRentalRate($rentalRate)
    {
        $this->rentalRate = $rentalRate;

        return $this;
    }

    /**
     * Get rentalRate
     *
     * @return string 
     */
    public function getRentalRate()
    {
        return $this->rentalRate;
    }

    /**
     * Set length
     *
     * @param integer $length
     * @return Film
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return integer 
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set replacementCost
     *
     * @param string $replacementCost
     * @return Film
     */
    public function setReplacementCost($replacementCost)
    {
        $this->replacementCost = $replacementCost;

        return $this;
    }

    /**
     * Get replacementCost
     *
     * @return string 
     */
    public function getReplacementCost()
    {
        return $this->replacementCost;
    }

    /**
     * Set rating
     *
     * @param string $rating
     * @return Film
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set specialFeatures
     *
     * @param string $specialFeatures
     * @return Film
     */
    public function setSpecialFeatures($specialFeatures)
    {
        $this->specialFeatures = $specialFeatures;

        return $this;
    }

    /**
     * Get specialFeatures
     *
     * @return string 
     */
    public function getSpecialFeatures()
    {
        return $this->specialFeatures;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     * @return Film
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
     * Set language
     *
     * @param \Application\Entity\Language $language
     * @return Film
     */
    public function setLanguage(\Application\Entity\Language $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Application\Entity\Language 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set originalLanguage
     *
     * @param \Application\Entity\Language $originalLanguage
     * @return Film
     */
    public function setOriginalLanguage(\Application\Entity\Language $originalLanguage = null)
    {
        $this->originalLanguage = $originalLanguage;

        return $this;
    }

    /**
     * Get originalLanguage
     *
     * @return \Application\Entity\Language 
     */
    public function getOriginalLanguage()
    {
        return $this->originalLanguage;
    }

    /**
     * Add actor
     *
     * @param \Application\Entity\Actor $actor
     * @return Film
     */
    public function addActor(\Application\Entity\Actor $actor)
    {
        $this->actor[] = $actor;

        return $this;
    }

    /**
     * Remove actor
     *
     * @param \Application\Entity\Actor $actor
     */
    public function removeActor(\Application\Entity\Actor $actor)
    {
        $this->actor->removeElement($actor);
    }

    /**
     * Get actor
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * Add category
     *
     * @param \Application\Entity\Category $category
     * @return Film
     */
    public function addCategory(\Application\Entity\Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Application\Entity\Category $category
     */
    public function removeCategory(\Application\Entity\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
