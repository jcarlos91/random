<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 4/09/19
 * Time: 11:58 AM
 */

namespace AppBundle\Form\Type\Front\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FormInvitedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('evento',TextType::class,[
                'label' => 'Nombre Evento',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('invited',TextType::class,[
                'label' => 'Nombre Invitado',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('email',EmailType::class,[
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('fecha', DateType::class, [
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
                ],
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'js-datepicker form-control'
                ],
                'required' =>true
            ])
            ->add('save', SubmitType::class,[
                'label' => 'Guardar',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

}