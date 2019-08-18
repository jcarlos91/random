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
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('celular',TextType::class,[
                'label' => 'Teléfono Celular',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' =>true
            ])
            ->add('email',TextType::class,[
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
                    'class' => 'form-control'
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
                    'class' => 'form-control'
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
            ->add('fecha', DateTimeType::class, [
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