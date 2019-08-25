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
     * @var UserDetail
     *
     * @ORM\OneToOne(targetEntity="UserDetail", mappedBy="userId", cascade={"persist"})
     */
    protected $userDetail;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @return UserDetail
     */
    public function getUserDetail()
    {
        return $this->userDetail;
    }

    /**
     * @param UserDetail $userDetail
     */
    public function setUserDetail(UserDetail $userDetail)
    {
        $this->userDetail = $userDetail;
    }

}