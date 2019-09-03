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
use AppBundle\Form\Type\Front\Type\FilterType;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManager;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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

        $numEventTable = $em->getRepository('AppBundle:Evento')->findBy([
            'userCreated' => $user,
            'delete' => 0
        ]);

        return $this->render(':front/Default:index.html.twig',[
            'CountEvents' => $numEventTable,
            'numEvents' => $numEvent
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
                    $starDate = $data['start_date'];
                    $endDate = $data['end_date'];
                    $user = $this->getUser();
                    $events = $em->getRepository('AppBundle:Evento')->getEventByDates($starDate,$endDate,$user);
                    $session->set('_front_event_list',$events);
                }
            }
        }

        return $this->render('front/Eventos/list_events.html.twig',[
            'form' => $form->createView(),
            'events' => $events,
            'title' => 'Listado Registros'
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
        $fileName = 'listado_eventos_'.$today->format('U');

        return $this->exportExcel($columnNames, $columnValues, $fileName );
    }
}