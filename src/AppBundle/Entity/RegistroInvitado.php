<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegistroInvitado
 *
 * @ORM\Table(name="registro_invitado")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RegistroInvitadoRepository")
 */
class RegistroInvitado
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
     * @var string
     *
     * @ORM\Column(name="nombre_evento", type="string", length=255)
     */
    private $nombreEvento;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_invitado", type="string", length=255)
     */
    private $nombreInvitado;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var int
     *
     * @ORM\Column(name="deleted", type="integer")
     */
    private $delete;


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
     * Set nombreEvento
     *
     * @param string $nombreEvento
     *
     * @return RegistroInvitado
     */
    public function setNombreEvento($nombreEvento)
    {
        $this->nombreEvento = $nombreEvento;

        return $this;
    }

    /**
     * Get nombreEvento
     *
     * @return string
     */
    public function getNombreEvento()
    {
        return $this->nombreEvento;
    }

    /**
     * Set nombreInvitado
     *
     * @param string $nombreInvitado
     *
     * @return RegistroInvitado
     */
    public function setNombreInvitado($nombreInvitado)
    {
        $this->nombreInvitado = $nombreInvitado;

        return $this;
    }

    /**
     * Get nombreInvitado
     *
     * @return string
     */
    public function getNombreInvitado()
    {
        return $this->nombreInvitado;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return RegistroInvitado
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RegistroInvitado
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @return int
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * @param int $delete
     */
    public function setDelete($delete)
    {
        $this->delete = $delete;
    }
}

