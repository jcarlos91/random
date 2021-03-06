<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 3/09/19
 * Time: 05:08 PM
 */

namespace AppBundle\Form\Type\Front\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', TextType::class,[
                'label' => 'Empresa',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class,[
                'label' => 'Correo Electronico',
                'required'   => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('start_date', DateType::class,[
                'label' => 'Fecha Inicio',
                'widget' => 'single_text',
                'html5' => false,
                'required'   => false,
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'attr' => [
                    'class' => 'js-datepicker form-control'
                ]
            ])
            ->add('end_date', DateType::class,[
                'label' => 'Fecha Fin',
                'widget' => 'single_text',
                'required'   => false,
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'attr' => [
                    'class' => 'js-datepicker form-control'
                ]
            ])
            ->add('search', SubmitType::class,[
                'label' => 'Buscar',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

}