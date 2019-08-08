<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\UserDetail", mappedBy="userId")
     */
    protected $userdetail;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserdetail()
    {
        return $this->userdetail;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $userdetail
     */
    public function setUserdetail($userdetail)
    {
        $this->userdetail = $userdetail;
    }
}