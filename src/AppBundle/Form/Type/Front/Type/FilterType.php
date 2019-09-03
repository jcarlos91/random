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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start_date', DateType::class,[
                'label' => 'Fecha Inicio',
                'widget' => 'single_text',
                'html5' => false,
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
                'html5' => false,
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