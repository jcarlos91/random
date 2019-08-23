<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 23/08/19
 * Time: 04:43 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends BaseController
{
    /**
     * @Route("/index", name="front_homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request){
        return $this->render(':front/Default:index.html.twig');
    }

}