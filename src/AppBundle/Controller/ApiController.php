<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 17/02/20
 * Time: 01:48 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Evento;
use AppBundle\Entity\RegistroInvitado;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ApiController
 * @package AppBundle\Controller
 */
class ApiController extends BaseController
{
    /**
     * @Route("/api/invited/new/register", name="front_api_invited_register_event")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerInvitedAction(Request $request){
        $postData = $request->request->all();

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $eventName = $postData['eventName'];
        $invitedName = $postData['invitedName'];
        $email = $postData['email'];
        $date = new \DateTime($postData['date']); //format text año-mes-dia
        $description = $postData['description'];
        $cellphone = $postData['cellphone'];

        try {
            $em->getConnection()->beginTransaction();
            $em->getConnection()->setAutoCommit(false);
            $invitedRegister = new RegistroInvitado();
            $invitedRegister->setNombreEvento($eventName);
            $invitedRegister->setNombreInvitado($invitedName);
            $invitedRegister->setEmail($email);
            $invitedRegister->setFecha($date);
            $invitedRegister->setDelete(0);
            $invitedRegister->setDescripcion($description);
            $invitedRegister->setCelular($cellphone);
            $this->sendEmail($email,$invitedName);
            $this->sendEmailAdmin($email,$invitedName);
            $em->persist($invitedRegister);
            $em->flush();
            $em->getConnection()->commit();
            return $this->apiResponse(['data' => $this->get('translator')->trans('Register created successful')]);
        }catch (\Exception $e) {
            $em->getConnection()->rollBack();
            return $this->apiErrorResponse([$e->getMessage()]);
        }
    }

    /**
     * @Route("/api/register/new/event", name="front_api_register_new_event")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addEventsAction(Request $request){
        $postData = $request->request->all();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Evento $meeting */
        $meeting = new Evento();
        $em->getConnection()->beginTransaction();
        try {
            $associate = $this->getUser();
            $meeting->setUserCreated($associate);
            $meeting->setNombre($postData['firstName']);
            $meeting->setApellidoPaterno($postData['lastName']);
            $meeting->setApellidoMaterno($postData['lastName2']);
            $meeting->setCelular($postData['cellphone']);
            $meeting->setDireccion($postData['address']);
            $meeting->setCP($postData['cp']);
            $meeting->setEstado($postData['state']);
            $meeting->setMunicipio($postData['municipality']);
            $meeting->setEmpresa($postData['company']);
            $meeting->setFecha(new \DateTime('now'));
            $meeting->setEmail($postData['email']);
            $meeting->setDelete(0);
            $meeting->setDateCreated(new \DateTime('now'));
            $em->persist($meeting);
            $em->flush();
            $em->getConnection()->commit();
            return $this->apiResponse(['data'=> $this->get('translator')->trans('Event created correctly')]);
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();
            return $this->apiErrorResponse([$e->getMessage()]);
        }
    }

    /**
     * @Route("/api/get/events", name="front_api_get_events")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myEventsAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $dateStart = new \DateTime(date('Y-m-d', strtotime('first day of this month')));
        $dateEnd = new \DateTime(date('Y-m-d', strtotime('last day of this month')));

        $events = $em->getRepository('AppBundle:Evento')->getEvents($user,$dateStart, $dateEnd);
        $myEvents = [];
        /** @var Evento $event */
        foreach ($events as $event){
            $myEvent = [
                'id' => $event->getId(),
                'company' => $event->getEmpresa(),
                'name' => $event->getNombre(),
                'lastName' => $event->getApellidoPaterno(),
                'lastName2' => $event->getApellidoMaterno(),
                'email' => $event->getEmail(),
                'cellphone' => $event->getCelular(),
                'date' => $event->getDateCreated()->format('d-m-Y'),
                'address' => $event->getDireccion(),
                'cp' => $event->getCP(),
                'state' => $event->getEstado(),
                'municipality' => $event->getMunicipio()
            ];
            $myEvents [] = $myEvent;
        }

        return $this->apiResponse(['events' => $myEvents]);
    }

    /**
     * @Route("/api/profile/info", name="front_api_profile_info")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(Request $request){
        /** @var User $user */
        $user = $this->getUser();
        if($user){
            $profileInfo = [
                'name' => $user->getUserDetail()->getNombre(),
                'lastName' => $user->getUserDetail()->getApellidoPaterno(),
                'lastName2' => $user->getUserDetail()->getApellidoMaterno(),
                'email' => $user->getEmail(),
                'cellphone' => $user->getUserDetail()->getTelefonoCelular(),
                'birthday' => $user->getUserDetail()->getFechaNacimiento()->format('Y-m-d')
            ];
        }else{
            $profileInfo = [
                'name' => '',
                'lastName' => '',
                'lastName2' => '',
                'email' => '',
                'cellphone' => '',
                'birthday' => ''
            ];
        }

        return $this->apiResponse(['profile' => $profileInfo]);
    }

    /**
     * @Route("/api/events/download/events.xls", name="front_api_download_events")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function eventsDownloadAction(Request $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $data = $request->query->all();
        $starDate = new \DateTime($data['start_date']);
        $endDate = new \DateTime($data['end_date']);
        $user = $this->getUser();
        $events = $em->getRepository('AppBundle:Evento')->getEventByDates(null, null, $starDate, $endDate, $user);

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
        /** @var Evento $event */
        foreach ($events as $event){
            $value = [
                $event->getEmpresa(),
                $event->getNombre(),
                $event->getApellidoPaterno(),
                $event->getApellidoMaterno(),
                $event->getFecha()->format('d/m/Y H:m:i'),
                $event->getDireccion(),
                $event->getMunicipio(),
                $event->getEstado(),
                $event->getCP(),
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
     * @Route("/api/event/delete", name="front_api_delete_event")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function EventDeleteAction(Request $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $postData = $request->request->all();
        $event = $em->getRepository('AppBundle:Evento')->findOneBy([
            'id' => $postData['eventId'],
            'delete' => 0
        ]);
        if($event){
            $em->getConnection()->beginTransaction();
            try{
                $event->setDelete(1);
                $event->setUserModified($user);
                $event->setDateModified(new \DateTime('now'));
                $em->flush();
                $em->getConnection()->commit();
                return $this->apiResponse(['data'=> $this->get('translator')->trans('Event delete correctly')]);
            }catch (\Exception $e){
                $em->getConnection()->rollBack();
                return $this->apiErrorResponse([$e->getMessage()]);
            }
        }else{
            return $this->apiErrorResponse([$this->get('translator')->trans('Event not found')],404);
        }
    }

    /**
     * @Route("/api/events/save", name="front_api_events_massive")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function saveEventsAction(Request $request){
        $postData = $request->request->all();

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $events = $postData['events'];
        $em->getConnection()->beginTransaction();
        try {
            foreach ($events as $event) {
                $meeting = new Evento();
                $associate = $this->getUser();
                $meeting->setUserCreated($associate);
                $meeting->setNombre($event['firstName']);
                $meeting->setApellidoPaterno($event['lastName']);
                $meeting->setApellidoMaterno($event['lastName2']);
                $meeting->setCelular($event['cellphone']);
                $meeting->setDireccion($event['address']);
                $meeting->setCP($event['cp']);
                $meeting->setEstado($event['state']);
                $meeting->setMunicipio($event['municipality']);
                $meeting->setEmpresa($event['company']);
                $meeting->setFecha(new \DateTime('now'));
                $meeting->setEmail($event['email']);
                $meeting->setDelete(0);
                $meeting->setDateCreated(new \DateTime('now'));
                $em->persist($meeting);
            }
            $em->flush();
            $em->getConnection()->commit();
            return $this->apiResponse(['data' => $this->get('translator')->trans('Events created correctly')]);
        }catch (\Exception $e){
            $em->getConnection()->rollBack();
            return $this->apiErrorResponse([$e->getMessage()]);
        }
    }
}