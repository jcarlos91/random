<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evento
 *
 * @ORM\Table(name="eventos")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventoRepository")
 */
class Evento
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
     * @ORM\Column(name="evento", type="string", length=255)
     */
    private $evento;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido_paterno", type="string", length=255)
     */
    private $apellidoPaterno;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido_materno", type="string", length=255)
     */
    private $apellidoMaterno;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="celular", type="string", length=255)
     */
    private $celular;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=255)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="municipio", type="string", length=255)
     */
    private $municipio;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=255)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="empresa", type="string", length=255)
     */
    private $empresa;

    /**
     * @var string
     *
     * @ORM\Column(name="fecha", type="string", length=255)
     */
    private $fecha;

    /**
     * @var \AppBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_created", referencedColumnName="id")
     * })
     */
    private $userCreated;

    /**
     * @var \AppBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_modified", referencedColumnName="id", nullable=true)
     * })
     */
    private $userModified;

    /**
     * @var int
     *
     * @ORM\Column(name="deleted", type="integer")
     */
    private $delete;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=true)
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
     * Set userCreated
     *
     * @param \AppBundle\Entity\User $userCreated
     *
     * @return Evento
     */
    public function setUserCreated(User $userCreated)
    {
        $this->userCreated = $userCreated;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->userCreated;
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

    /**
     * @return User
     */
    public function getUserModified()
    {
        return $this->userModified;
    }

    /**
     * @param User $userModified
     */
    public function setUserModified(User $userModified)
    {
        $this->userModified = $userModified;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Evento
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
     * @return Evento
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

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getApellidoPaterno()
    {
        return $this->apellidoPaterno;
    }

    /**
     * @param string $apellidoPaterno
     */
    public function setApellidoPaterno($apellidoPaterno)
    {
        $this->apellidoPaterno = $apellidoPaterno;
    }

    /**
     * @return string
     */
    public function getApellidoMaterno()
    {
        return $this->apellidoMaterno;
    }

    /**
     * @param string $apellidoMaterno
     */
    public function setApellidoMaterno($apellidoMaterno)
    {
        $this->apellidoMaterno = $apellidoMaterno;
    }

    /**
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param string $celular
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;
    }

    /**
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param string $direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * @return string
     */
    public function getCP()
    {
        return $this->cp;
    }

    /**
     * @param string $cp
     */
    public function setCP($cp)
    {
        $this->cp = $cp;
    }

    /**
     * @return string
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * @param string $municipio
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
    }

    /**
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return string
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param string $empresa
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @return string
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param string $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * @return string
     */
    public function getEvento()
    {
        return $this->evento;
    }

    /**
     * @param string $evento
     */
    public function setEvento($evento)
    {
        $this->evento = $evento;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}

