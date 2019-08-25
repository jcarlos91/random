<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 10/12/18
 * Time: 04:37 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Entity\UserDetail;
use AppBundle\Form\Type\RegistrationType;
use AppBundle\Form\Type\UserType;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    /**
     * @Route("/admin/usuarios/list", name="back_user_list")
     * @param Request $request
     * @return Response
     */
    public function getUserListAction(Request $request){
        $postData = $request->query->all();
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/user_list.html.twig',[
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/usuario/nuevo", name="back_user_new")
     * @param Request $request
     * @return Response
     * @throws ConnectionException
     */
    public function newUserAction(Request $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($request->getMethod() == 'POST'){
            if($form->isSubmitted() && $form->isValid()){
                $save = $form->get('save')->isClicked();
                if($save){
                    try{
                        $data = $form->getData();
                        $role = $form->get('roles')->getData();
                        $em->getConnection()->beginTransaction();
                        $user->addRole($role);
                        $user->setEnabled(true);
                        $em->persist($user);
                        $user->getUserDetail()->setUserId($user);
                        $em->flush();
                        $em->getConnection()->commit();
                        $this->addFlash('success','Usuario creado correctamente');
                        return $this->redirectToRoute('back_user_list');
                    }catch (\Exception $e){
                        $em->getConnection()->rollBack();
                        $this->addFlash(
                            'danger', $e->getMessage()
                        );
                    }

                }
            }
        }

        return $this->render('user/user_new.html.twig',[
            'form' => $form->createView(),
            'title' => 'Alta Usuario'
        ]);
    }

    /**
     * @Route("admin/usuario/{user}/edicion", name="back_user_edit")
     * @param Request $request
     * @param User $user
     * @return RedirectResponse|Response
     * @throws ConnectionException
     */
    public function editUserAction(Request $request, User $user){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($request->getMethod() == 'POST') {
            if ($form->isSubmitted() && $form->isValid()) {
                $save = $form->get('save')->isClicked();
                if ($save) {
                    try {
                        $role = $form->get('roles')->getData();
                        $em->getConnection()->beginTransaction();
                        $user->addRole($role);
                        $user->setEnabled(true);
                        $em->persist($user);
                        $user->getUserDetail()->setUserId($user);
                        $em->flush();
                        $em->getConnection()->commit();
                        $this->addFlash('success', 'Usuario actualizado correctamente');
                        return $this->redirectToRoute('back_user_list');
                    } catch (\Exception $e) {
                        $em->getConnection()->rollBack();
                        $this->addFlash(
                            'danger', $e->getMessage()
                        );
                    }

                }
            }
        }

        return $this->render('user/user_new.html.twig',[
            'form' => $form->createView(),
            'title' => 'Actualización Usuario'
        ]);
    }

    /**
     * @Route("/admin/usuario/{user}/status/{status}", name="back_user_status")
     * @param Request $request
     * @param User $user
     * @param $status
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function changeStatusUserAction(Request $request, User $user, $status){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        if($status == 'enable'){
            $userStatus = true;
            $message = 'Habilitado';
        }else{
            $userStatus = false;
            $message = 'Deshabilitado';
        }
        try{
            $em->getConnection()->beginTransaction();
            if ($user) {
                $user->setEnabled($userStatus);
                $em->flush();
                $em->getConnection()->commit();
                $this->addFlash('success', 'Usuario '.$message.' correctamente');
            }
        }catch (\Exception $e){
            $em->getConnection()->rollBack();
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
        return $this->redirectToRoute('back_user_list');
    }

    /**
     * @Route("/admin/usuarios/export", name="back_export_users")
     */
    public function exportToExcelUser(){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $columnNames = [
            'Usuario',
            'Email',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Role',
            'CURP',
            'NSS',
            'RFC',
            'Telefono Casa',
            'Telefono Celular',
            'Calle',
            'No. Interior',
            'No. Exterior',
            'Colonia',
            'Delegacion',
            'Municipio',
            'Ciudad',
            'CP',
            'Fecha Nacimiento',
            'Estatus'
        ];

        $columnValues = [];
        $users = $em->getRepository('AppBundle:User')->findAll();
        foreach ($users as $user){
            $value = [
                $user->getUsername(),
                $user->getEmail(),
                $user->getUserDetail()->getNombre(),
                $user->getUserDetail()->getApellidoPaterno(),
                $user->getUserDetail()->getApellidoMaterno(),
                implode(",", $user->getRoles()),
                $user->getUserDetail()->getCurp(),
                $user->getUserDetail()->getNss(),
                $user->getUserDetail()->getRfc(),
                $user->getUserDetail()->getTelefonoCasa(),
                $user->getUserDetail()->getTelefonoCelular(),
                $user->getUserDetail()->getCalle(),
                $user->getUserDetail()->getNoInt(),
                $user->getUserDetail()->getNoExt(),
                $user->getUserDetail()->getColonia(),
                $user->getUserDetail()->getMunicipio(),
                $user->getUserDetail()->getCiudad(),
                $user->getUserDetail()->getCP(),
                $user->getUserDetail()->getFechaNacimiento()->format('d/m/Y'),
                ($user->isEnabled() ? 'Activo': 'Inactivo')
            ];
            $columnValues [] = $value;
        }
        $today = new \DateTime('now');
        $fileName = 'listado_usuarios_'.$today->format('U');

        return $this->exportExcel($columnNames, $columnValues, $fileName );
    }
}