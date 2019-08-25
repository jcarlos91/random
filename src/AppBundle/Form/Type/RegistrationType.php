<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',TextType::class,[
                'label' => 'Nombre',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('apellidoPaterno',TextType::class,[
                'label' => 'Apellido Paterno',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('apellidoMaterno',TextType::class,[
                'label' => 'Apellido Materno',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('curp',TextType::class,[
                'label' => 'CURP',
                'attr' =>  [
                    'class' => 'form-control',
                    'pattern'=>'[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][A-Z]{2}[B,C,D,F,G,H,J,K,L,M,N,Ñ,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9]',
                    'title' => 'Ingresa un CURP válido'
                ],
                'required' => true
            ])
            ->add('nss', TextType::class,[
                'label' => 'NSS',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('rfc',TextType::class,[
                'label' => 'RFC',
                'attr' =>  [
                    'class' => 'form-control',
                    'pattern'=>'[A-Z,Ñ,&amp;]{4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?',
                    'title' => 'Ingresa un RFC válido'
                ],
                'required' => true
            ])
            ->add('telefonoCasa',TextType::class,[
                'label' => 'Teléfono de Casa',
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => '[0-9]{2}[0-9]{4}[0-9]{4}',
                    'title' => 'Ingresa un número telefónico  válido',
                    'maxlength' => 10
                ]
            ])
            ->add('telefonoCelular',TextType::class,[
                'label' => 'Teléfono Celular',
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => '[0-9]{2}[0-9]{4}[0-9]{4}',
                    'title' => 'Ingresa un número telefónico  válido',
                    'maxlength' => 10
                ]
            ])
            ->add('calle',TextType::class,[
                'label' => 'Calle',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('noInt',TextType::class,[
                'label' => 'No. Interior',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 10
                ]
            ])
            ->add('noExt',TextType::class,[
                'label' => 'No. Exterior',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 10
                ]
            ])
            ->add('colonia',TextType::class,[
                'label' => 'Colonia',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true
            ])
            ->add('delegacion',TextType::class,[
                'label' => 'Delegación',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true
            ])
            ->add('municipio',TextType::class,[
                'label' => 'Municipio',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true
            ])
            ->add('ciudad',TextType::class,[
                'label' => 'Ciudad',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true
            ])
            ->add('cp',TextType::class,[
                'label' => 'Codigó Postal',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 5
                ],
                'required' => true
            ])
            ->add('fechaNacimiento',DateType::class,[
                'label' => 'Fecha de Nacimiento',
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
        ;
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}