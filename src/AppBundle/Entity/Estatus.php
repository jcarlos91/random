<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estatus
 *
 * @ORM\Table(name="estatus")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EstatusRepository")
 */
class Estatus
{
    const PENDIENTE = 'PENDIENTE';
    const FINALIZADO = 'FINALIZADO';
    const CANCELADO = 'CANCELADO';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="estatus", type="string", length=255)
     */
    private $estatus;

    /**
     * @var int
     *
     * @ORM\Column(name="deleted", type="integer")
     */
    private $deleted;

    /**
     * @var int
     *
     * @ORM\Column(name="userCreated", type="integer")
     */
    private $userCreated;

    /**
     * @var int
     *
     * @ORM\Column(name="userModified", type="integer")
     */
    private $userModified;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModified", type="datetime")
     */
    private $dateModified;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set estatus
     *
     * @param string $estatus
     *
     * @return Estatus
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;

        return $this;
    }

    /**
     * Get estatus
     *
     * @return string
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * Set deleted
     *
     * @param integer $deleted
     *
     * @return Estatus
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return int
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set userCreated
     *
     * @param integer $userCreated
     *
     * @return Estatus
     */
    public function setUserCreated($userCreated)
    {
        $this->userCreated = $userCreated;

        return $this;
    }

    /**
     * Get userCreated
     *
     * @return int
     */
    public function getUserCreated()
    {
        return $this->userCreated;
    }

    /**
     * Set userModified
     *
     * @param integer $userModified
     *
     * @return Estatus
     */
    public function setUserModified($userModified)
    {
        $this->userModified = $userModified;

        return $this;
    }

    /**
     * Get userModified
     *
     * @return int
     */
    public function getUserModified()
    {
        return $this->userModified;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Estatus
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateModified
     *
     * @param \DateTime $dateModified
     *
     * @return Estatus
     */
    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    public function __toString()
    {
        return $this->estatus;
    }
}

