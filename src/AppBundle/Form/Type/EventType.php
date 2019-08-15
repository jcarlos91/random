<?php

namespace AppBundle\Form\Type;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

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
                'placeholder' => 'Evento',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('nombre',TextType::class,[
                'label' => 'Nombre',
                'placeholder' => 'Nombre',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('apellidoPaterno',TextType::class,[
                'label' => 'Apellido Paterno',
                'placeholder' => 'Apellido Paterno',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('apellidoMaterno',TextType::class,[
                'label' => 'Apellido Materno',
                'placeholder' => 'Apellido Materno',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('telefono',TextType::class,[
                'label' => 'Teléfono',
                'placeholder' => 'Teléfono',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('celular',TextType::class,[
                'label' => 'Teléfono Celular',
                'placeholder' => 'Teléfono Celular',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('email',TextType::class,[
                'label' => 'Email',
                'placeholder' => 'Email',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('direccion',TextType::class,[
                'label' => 'Dirección',
                'placeholder' => 'Dirección',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('cp',TextType::class,[
                'label' => 'Código Postal',
                'placeholder' => 'Código Postal',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('municipio',TextType::class,[
                'label' => 'Municipio',
                'placeholder' => 'Municipio',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('estado',TextType::class,[
                'label' => 'Estado',
                'placeholder' => 'Estado',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('empresa',TextType::class,[
                'label' => 'Empresa',
                'placeholder' => 'Empresa',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('fecha', DateTimeType::class, [
                'label' => 'Fecha Inicio',
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
                ],
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
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