<?php
/**
 * Created by PhpStorm.
 * User: br4in
 * Date: 8/19/18
 * Time: 4:14 PM
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Colocation
 *
 * @ORM\Entity
 * @ORM\Table(name="colocation_amenities")
 */

class Colocation_Amenity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Colocation
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Colocation")
     * @ORM\JoinColumn(name="colocation", referencedColumnName="id",onDelete="CASCADE")
     */
    private $colocation;

    /**
     * @var Amenity
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Amenity")
     * @ORM\JoinColumn(name="amenity", referencedColumnName="id",onDelete="CASCADE")
     */
    private $amenity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valid;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Colocation
     */
    public function getColocation(): Colocation
    {
        return $this->colocation;
    }

    /**
     * @param int $colocation
     */
    public function setColocation(Colocation $colocation): void
    {
        $this->colocation = $colocation;
    }

    /**
     * @return Amenity
     */
    public function getAmenity(): Amenity
    {
        return $this->amenity;
    }

    /**
     * @param int $amenity
     */
    public function setAmenity(Amenity $amenity): void
    {
        $this->amenity = $amenity;
    }

    /**
     * @return mixed
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @param mixed $valid
     */
    public function setValid($valid): void
    {
        $this->valid = $valid;
    }
}