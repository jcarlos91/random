<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 23/08/19
 * Time: 04:43 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Evento;
use AppBundle\Form\Type\EventType;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManager;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $numEvent = $em->getRepository('AppBundle:Evento')->findBy([
            'userCreated' => $user,
            'delete' => 0
        ]);

        return $this->render(':front/Default:index.html.twig',[
            'numEvents' => $numEvent
        ]);
    }

    /**
     * @Route("/index/nuevo/evento", name="front_new_event")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws ConnectionException
     */
    public function newEvent(REquest $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $meeting = new Evento();
        $form = $this->createForm(EventType::class);

        $form->handleRequest($request);

        if ($request->getMethod() == 'POST') {
            if ($form->isValid() && $form->isSubmitted()) {
                $save = $form->get('save')->isClicked();
                if ($save) {
                    $em->getConnection()->beginTransaction();
                    try {
                        $associate = $this->getUser();
                        $meeting->setUserCreated($associate);
                        $meeting->setEvento($form->get('evento')->getData());
                        $meeting->setNombre($form->get('nombre')->getData());
                        $meeting->setApellidoPaterno($form->get('apellidoPaterno')->getData());
                        $meeting->setApellidoMaterno($form->get('apellidoMaterno')->getData());
                        $meeting->setCelular($form->get('celular')->getData());
                        $meeting->setTelefono($form->get('telefono')->getData());
                        $meeting->setDireccion($form->get('direccion')->getData());
                        $meeting->setCP($form->get('cp')->getData());
                        $meeting->setEstado($form->get('estado')->getData());
                        $meeting->setMunicipio($form->get('municipio')->getData());
                        $meeting->setEmpresa($form->get('empresa')->getData());
                        $meeting->setFecha($form->get('fecha')->getData());
                        $meeting->setEmail($form->get('email')->getData());
                        $meeting->setDelete(0);
                        $meeting->setDateCreated(new \DateTime('now'));
                        $em->persist($meeting);
                        $em->flush();
                        $em->getConnection()->commit();
                        $this->addFlash(
                            'success', 'Evento creado correctamente'
                        );
                        return $this->redirectToRoute('front_homepage');
                    } catch (Exception $e) {
                        $em->getConnection()->rollBack();
                        $this->addFlash(
                            'danger', $e->getMessage()
                        );
                    }
                }
            } else {
                $errors = [];
                foreach ($form->getErrors() as $error) {
                    $errors[] = $error;
                }
                $this->addFlash(
                    'danger', $errors
                );
            }
        }

        return $this->render('front/Eventos/new_event.html.twig', [
            'form' => $form->createView()
        ]);
    }
}