<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Citas;
use AppBundle\Entity\Estatus;
use AppBundle\Entity\Evento;
use AppBundle\Form\Type\EventType;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    /**
     * @Route("/admin", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('AppBundle:Evento')->findBy(['delete'=>0]);
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/admin/new/event", name="new_event")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function newEventAction(Request $request)
    {
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
                        return $this->redirectToRoute('homepage');
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

        return $this->render('default/new_event.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/delete/{event}/event/", name="delete_event")
     * @param Evento $event
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function deleteEventAction(Evento $event)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        try {
            $em->getConnection()->beginTransaction();
            if ($event) {
                $event->setDelete(1);

                $em->flush();
                $em->getConnection()->commit();
                $this->addFlash('success', 'Evento eliminado correctamente');
            }
        } catch (Exception $e) {
            $em->getConnection()->rollBack();
            $this->addFlash(
                'danger', $e->getMessage()
            );
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/admin/update/{event}/event/", name="update_event")
     * @param Request $request
     * @param Evento $event
     * @return Response
     * @throws ConnectionException
     */
    public function updateEventAction(Request $request, Evento $event){

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(EventType::class,$event);
        $form->handleRequest($request);

        if($request->getMethod() == 'POST'){
            if($form->isValid() && $form->isSubmitted()){
                $save = $form->get('save')->isClicked();
                if($save){
                    try{
                        $em->getConnection()->beginTransaction();
                        $event->setUserModified($this->getUser());
                        $event->setDateModified(new \DateTime('now'));
                        $em->flush();
                        $em->getConnection()->commit();
                        $this->addFlash('success','Evento actualizado correctamente');
                        return $this->redirectToRoute('homepage');
                    }catch (Exception $e){
                        $em->getConnection()->rollBack();
                        $this->addFlash(
                            'danger', $e->getMessage()
                        );
                    }
                }
            }
        }

        return $this->render('default/update_event.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/event/export", name="export_event")
     */
    public function exportToExcelEvent(){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $columnNames = [
            'Evento',
            'Empresa',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Fecha',
            'Direccion',
            'Municipio',
            'Estado',
            'CP',
            'Telefono',
            'Celular',
            'Email'
        ];

        $columnValues = [];
        $events = $em->getRepository('AppBundle:Evento')->findBy(['delete'=>0]);
        foreach ($events as $event){
            $value = [
                $event->getEvento(),
                $event->getEmpresa(),
                $event->getNombre(),
                $event->getApellidoPaterno(),
                $event->getApellidoMaterno(),
                $event->getFecha()->format('d/m/Y H:m:i'),
                $event->getDireccion(),
                $event->getMunicipio(),
                $event->getEstado(),
                $event->getCP(),
                $event->getTelefono(),
                $event->getCelular(),
                $event->getEmail()
            ];
            $columnValues [] = $value;
        }
        $today = new \DateTime('now');
        $fileName = 'listado_eventos_'.$today->format('U');

        return $this->exportExcel($columnNames, $columnValues, $fileName );
    }
}
