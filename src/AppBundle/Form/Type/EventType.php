<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * Created by PhpStorm.
 * User: charly
 * Date: 10/12/18
 * Time: 02:49 PM
 */
class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('evento',TextType::class,[
                'label' => 'Evento',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('nombre',TextType::class,[
                'label' => 'Nombre',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('apellidoPaterno',TextType::class,[
                'label' => 'Apellido Paterno',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('apellidoMaterno',TextType::class,[
                'label' => 'Apellido Materno',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('telefono',TextType::class,[
                'label' => 'Teléfono',
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => '[0-9]{2}[0-9]{4}[0-9]{4}',
                    'title' => 'Ingresa un número telefónico  válido',
                    'maxlength' => 10
                ],
                'required' =>true
            ])
            ->add('celular',TextType::class,[
                'label' => 'Teléfono Celular',
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => '[0-9]{2}[0-9]{4}[0-9]{4}',
                    'title' => 'Ingresa un número telefónico  válido',
                    'maxlength' => 10
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
            ->add('direccion',TextType::class,[
                'label' => 'Dirección',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('cp',TextType::class,[
                'label' => 'Código Postal',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 5
                ],
                'required' =>true
            ])
            ->add('municipio',TextType::class,[
                'label' => 'Municipio',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('estado',TextType::class,[
                'label' => 'Estado',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' =>true
            ])
            ->add('empresa',TextType::class,[
                'label' => 'Empresa',
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
                'required' =>false
            ])
            ->add('save',SubmitType::class,[
                'label' => 'Guardar',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])

        ;
    }

}