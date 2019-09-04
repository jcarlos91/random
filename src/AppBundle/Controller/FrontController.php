<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 23/08/19
 * Time: 04:43 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Evento;
use AppBundle\Entity\RegistroInvitado;
use AppBundle\Form\Type\EventType;
use AppBundle\Form\Type\Front\Type\FilterInvitedType;
use AppBundle\Form\Type\Front\Type\FilterType;
use AppBundle\Form\Type\Front\Type\FormInvitedType;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManager;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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
        ],['id'=>'DESC'],10);

        $RecordsInvited = $em->getRepository('AppBundle:RegistroInvitado')->findBy([
            'delete' => 0,
        ],['id' => 'DESC'],10);

        $numEventTable = $em->getRepository('AppBundle:Evento')->findBy([
            'userCreated' => $user,
            'delete' => 0
        ]);

        $numRecordInvited = $em->getRepository('AppBundle:RegistroInvitado')->findBy([
            'delete' => 0,
        ],['id' => 'DESC']);

        return $this->render(':front/Default:index.html.twig',[
            'CountEvents' => $numEventTable,
            'numEvents' => $numEvent,
            'numRecordInvited' => $numRecordInvited,
            'recordsInvited' => $RecordsInvited
        ]);
    }

    /**
     * @Route("/index/nuevo/evento", name="front_new_event")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws ConnectionException
     */
    public function newEvent(Request $request){
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
                        return $this->redirectToRoute('front_list_records');
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

    /**
     * @Route("/index/listado/registros", name="front_list_records")
     * @param Request $request
     * @return Response
     */
    public function EventsListAction(Request $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);
        $events = [];

        if($request->getMethod() == 'POST'){
            if($form->isValid() && $form->isSubmitted()){
                $search = $form->get('search')->isClicked();
                if($search){
                    $session = $this->get('session');
                    $session->set('_front_event_list',[]);
                    $data = $form->getData();
                    $company = $data['company'];
                    $email = $data['email'];
                    $starDate = $data['start_date'];
                    $endDate = $data['end_date'];
                    $user = $this->getUser();
                    $events = $em->getRepository('AppBundle:Evento')->getEventByDates($company, $email, $starDate, $endDate, $user);
                    $session->set('_front_event_list',$events);
                }
            }
        }

        return $this->render('front/Eventos/list_events.html.twig',[
            'form' => $form->createView(),
            'events' => $events,
            'title' => 'Records List'
        ]);

    }

    /**
     * @Route("/index/listado/registros/export", name="front_export_record_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportListEvent(Request $request){

        /** @var Session $session */
        $session = $this->get('session');
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
        $events = $session->get('_front_event_list');
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
        $fileName = 'listado_registros_'.$today->format('U');

        return $this->exportExcel($columnNames, $columnValues, $fileName );
    }

    /**
     * @Route("/index/inicial/nuevo/registro", name="front_invited_register_event")
     * @param Request $request
     * @return Response
     */
    public function invitedRegisterAction(Request $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(FormInvitedType::class);
        $form->handleRequest($request);

        if($request->getMethod() == 'POST'){
            if($form->isValid() && $form->isSubmitted()){
                $save = $form->get('save')->isClicked();
                if($save){
                    $data = $form->getData();
                    $eventName = $data['evento'];
                    $invitedName = $data['invited'];
                    $email = $data['email'];
                    $date = $data['fecha'];
                    try {
                        $em->getConnection()->beginTransaction();
                        $em->getConnection()->setAutoCommit(false);
                        $invitedRegister = new RegistroInvitado();
                        $invitedRegister->setNombreEvento($eventName);
                        $invitedRegister->setNombreInvitado($invitedName);
                        $invitedRegister->setEmail($email);
                        $invitedRegister->setFecha($date);
                        $invitedRegister->setDelete(0);
                        $em->persist($invitedRegister);
                        $em->flush();
                        $em->getConnection()->commit();
                        $this->addFlash(
                            'success', $this->get('translator')->trans('Register created successful')
                        );
                        $this->sendEmail($email,$invitedName);
                        return $this->redirectToRoute('front_initial_page');
                    }catch (Exception $e) {
                        $em->getConnection()->rollBack();
                        $this->addFlash(
                            'danger', $e->getMessage()
                        );
                    }
                }
            }
        }

        return $this->render('front/Invited/new_register.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/index/listado/registro/inivtados", name="front_invited_list")
     * @param Request $request
     * @return Response
     */
    public function guestListAction(Request $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Session $session */
        $session = $this->get('session');

        $form = $this->createForm(FilterInvitedType::class);
        $form->handleRequest($request);

        $eventsInvited = [];
        if($request->getMethod() == 'POST'){
            if($form->isValid() && $form->isSubmitted()){
                $search = $form->get('search')->isClicked();
                if($search){
                    $session->set('_invited_list',[]);
                    $data = $form->getData();
                    $event = $data['evento'];
                    $email = $data['email'];
                    $starDate = $data['start_date'];
                    $endDate = $data['end_date'];
                    $eventsInvited = $em->getRepository('AppBundle:RegistroInvitado')->getEventsByFilters($event, $email, $starDate, $endDate);
                    $session->set('_invited_list',$eventsInvited);
                }
            }
        }

        return $this->render('front/Invited/list.html.twig',[
            'form' => $form->createView(),
            'events' => $eventsInvited,
            'title' => 'Records List'
        ]);
    }

    /**
     * @Route("/index/invited/registros/export", name="front_export_record_list_invited")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportListInvitedEvent(Request $request){

        /** @var Session $session */
        $session = $this->get('session');
        $columnNames = [
            'Nombre Evento',
            'Nombre Invitado',
            'Fecha',
            'Email'
        ];

        $columnValues = [];
        $events = $session->get('_invited_list');
        foreach ($events as $event){
            $value = [
                $event->getNombreEvento(),
                $event->getNombreInvitado(),
                $event->getFecha()->format('d/m/Y H:m:i'),
                $event->getEmail()
            ];
            $columnValues [] = $value;
        }
        $today = new \DateTime('now');
        $fileName = 'listado_registro_invitados_'.$today->format('U');

        return $this->exportExcel($columnNames, $columnValues, $fileName );
    }
}