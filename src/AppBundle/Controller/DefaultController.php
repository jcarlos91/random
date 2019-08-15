<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Citas;
use AppBundle\Entity\Estatus;
use AppBundle\Entity\Evento;
use AppBundle\Form\Type\EventType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
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
                        $meeting->setNombre($form->get('nombre')->getData());
                        $meeting->setApellidoPaterno($form->get('apellidoPaterno')->getData());
                        $meeting->setApellidoMaterno($form->get('apellidoMaterno')->getData());
                        $meeting->setCelular($form->get('celular')->getData());
                        $meeting->setTelefono($form->get('telefono')->getData());
                        $meeting->setDireccion($form->get('direccion')->getData());
                        $meeting->setCP($form->get('cp')->getData());
                        $meeting->setEstado($form->get('estatdo')->getData());
                        $meeting->setMunicipio($form->get('municipio')->getData());
                        $meeting->setEmpresa($form->get('empresa')->getData());
                        $meeting->setFecha($form->get('fecha')->getData());
                        $meeting->setEmail($form->get('empresa')->getData());
                        $meeting->setDelete(0);
                        $meeting->setDateCreated(new \DateTime('now'));
                        $em->persist($meeting);
                        $em->flush();
                        $em->getConnection()->commit();
                        $this->addFlash(
                            'success', 'Evento creado correctamente'
                        );
                        return $this->redirectToRoute('homepage');
                    } catch (\Exception $e) {
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
     * @Route("/admin/delete/{event}/event/{meeting}", name="delete_event")
     * @param $event
     * @param $meeting
     * @return RedirectResponse
     */
    public function deleteEventAction($event, $meeting)
    {
        $em = $this->getDoctrine()->getManager();
        $cita = $em->getRepository('AppBundle:Citas')->find($meeting);
        try {
            $em->getConnection()->beginTransaction();
            if ($cita) {
                $cita->setDelete(1);
                $this->deleteEventCalendar($event);
                $em->flush();
                $em->getConnection()->commit();
                $this->addFlash('success', 'Evento eliminado correctamente');
            }
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();
            $this->addFlash(
                'danger', $e->getMessage()
            );
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/admin/update/{event}/event/{meeting}", name="update_event")
     * @param Request $request
     * @param $event
     * @param $meeting
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateEventAction(Request $request, $event, $meeting){
        $em = $this->getDoctrine()->getManager();
        $cita = $em->getRepository('AppBundle:Citas')->find($meeting);

        $form = $this->createForm(EventType::class,$cita);
        $form->remove('user');
        $form->remove('userHidden');

        $form->handleRequest($request);

        if($request->getMethod() == 'POST'){
            if($form->isValid() && $form->isSubmitted()){
                $save = $form->get('save')->isClicked();
                if($save){
                    try{
                        $em->getConnection()->beginTransaction();
                        $eventStart = $cita->getStartDateTime();
                        $eventEnd = $cita->getEndDateTime();
                        $eventSummary = $cita->getUser()->getUserdetail()->__toString();
                        $eventDescription = $cita->getId(). "-" . $form->get('observations')->getData();
                        $em->flush();
                        $em->getConnection()->commit();
                        $this->updateEvent(
                            $event,
                            $eventStart,
                            $eventEnd,
                            $eventSummary,
                            $eventDescription
                        );
                        $this->addFlash('success','Cita actualizada correctamente');
                        return $this->redirectToRoute('homepage');
                    }catch (\Exception $e){
                        $this->addFlash(
                            'danger', $e->getMessage()
                        );
                    }
                }
            }
        }

        return $this->render('default/update_event.html.twig',[
            'patient' => $cita->getUser(),
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
            'Titulo',
            'Observaciones',
            'Fecha Inicio',
            'Fecha Fin',
            'Usuario',
            'Estatus'
        ];

        $columnValues = [];
        $events = $em->getRepository('AppBundle:Evento')->findBy(['delete'=>0]);
        foreach ($events as $event){
            $value = [
                $event->getTitulo(),
                $event->getObservations(),
                $event->getStartDateTime()->format('d/m/Y H:m:i'),
                $event->getEndDateTime()->format('d/m/Y H:m:i'),
                $event->getUser()->__toString(),
                $event->getEstatus()->__toString()
            ];
            $columnValues [] = $value;
        }

        return $this->exportExcel($columnNames, $columnValues );
    }
}
