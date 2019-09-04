<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 11/12/18
 * Time: 09:43 AM
 */

namespace AppBundle\Controller;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BaseController extends Controller
{

    /**
     * @param $columnNames
     * @param $columnValues
     * @param $fileName
     * @return StreamedResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function exportExcel($columnNames, $columnValues, $fileName){
        $spreadsheet = new Spreadsheet();
        // Get active sheet - it is also possible to retrieve a specific sheet
        $sheet = $spreadsheet->getActiveSheet();

        $columnLetter = 'A';
        foreach ($columnNames as $columnName) {
            // Allow to access AA column if needed and more
            $sheet->setCellValue($columnLetter.'1', $columnName);
            $columnLetter++;
        }

        $i = 2; // Beginning row for active sheet
        foreach ($columnValues as $columnValue) {
            $columnLetter = 'A';
            foreach ($columnValue as $value) {
                $sheet->setCellValue($columnLetter.$i, $value);
                $columnLetter++;
            }
            $i++;
        }

        $response =  new StreamedResponse(
            function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$fileName.'.xls');
        $response->headers->set('Cache-Control','max-age=0');

        return $response->send();
    }

    public function sendEmail($email, $name ){
        // Create the Transport
        $transport = (new Swift_SmtpTransport(
            $this->get('service_container')->getParameter('mailer_host'),
            $this->get('service_container')->getParameter('mailer_port'),
            $this->get('service_container')->getParameter('mailer_encryption')
        ))
            ->setUsername($this->get('service_container')->getParameter('mailer_user'))
            ->setPassword($this->get('service_container')->getParameter('mailer_password'))
        ;

        $mailer = \Swift_Mailer::newInstance($transport);
        $from = $this->get('service_container')->getParameter('mailer_user');
        $message = new \Swift_Message($this->get('translator')->trans('Register'));
        $message
            ->setFrom($from)
            ->setTo($email)
            ->setBody(
                $this->renderView('front/Default/email_confirmation.html.twig',[
                    'name' => $name,
                ]),
                'text/html'
            )
        ;

        $mailer->send($message);
    }

}