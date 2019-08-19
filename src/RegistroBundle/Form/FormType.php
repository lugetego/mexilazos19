<?php

namespace RegistroBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;



class FormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'label'=>'*Nombre(s)',
                'required'=>true,
            ))
            ->add('apellidos', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'label'=>'*Apellidos',
                'required'=>true,

            ))
            ->add('sexo', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
                'choices'   => array(
                    false => 'Femenino',
                    true => 'Masculino'),
                'expanded' => true,
                'multiple' => false,
                'required'  => true,
                'label'  => '*Sexo',
                'choices_as_values' => false,
            ))
            ->add('mail', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'label'=>'*Correo electrónico',
                'required'=>true,

            ))
            ->add('institucion', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'label'=>'*Institución',
                'required'=>true,

            ))
            ->add('statuses', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
                'label'=>'*Estatus',
                'choices'=>array(
                    'Estudiante de licenciatura'=>'Estudiante de licenciatura',
                    'Estudiante de posgrado'=>'Estudiante de posgrado',
                    'Investigador'=>'Investigador',
                    'Posdoctorante'=>'Posdoctorante',
                    'Otro'=>'Otro'),
                'placeholder' => 'Seleccionar',
                'choices_as_values' => true,
                'mapped'=> false,
            ))
            ->add('status','Symfony\Component\Form\Extension\Core\Type\TextType', array(
                'label' => 'Otro estatus', 'read_only'=> true
            ));

        $formModifier = function (FormInterface $form, $otro) {

            if ( 'Otro' == $otro) {
                $form->add('status', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
                    'label' => 'Otro estatus',
                ));
            }

        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
                $formModifier($event->getForm(), $data->getStatus());

            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
                $val = $data['statuses'];
                if ( $val !='Otro') {
                    $data['status'] = $val;
                    $event->setData($data);
                }
            }
        );

        $builder->get('statuses')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $sport = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!

                $formModifier($event->getForm()->getParent(),$sport);

            }
        );

        $builder
            ->add('titulo', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'label'=>'Título de la plática',
                'required'=>false,
            ))
            ->add('resumen', 'Symfony\Component\Form\Extension\Core\Type\TextareaType',array(
                'label'=>'Resumen',
                'required'=>false,
            ))
            ->add('profesor', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'label'=>'Nombre de un profesor que haya accedido a escribir una referencia sobre usted',
                'required'=>false,
            ))
            ->add('instprofesor', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'label'=>'Institución del profesor',
                'required'=>false,

            ))
            ->add('mailprofesor', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'label'=>'Correo del profesor',
                'required'=>false,

            ))
            ->add('historialFile', 'vich_file', array(
                'required'=> false,
                'label' => 'Historial académico (en caso de ser estudiante)'
            ))

            ->add('cartaFile', 'vich_file', array(
                'required' => false,
                'label' => 'Carta de recomendación'
            ))

            ->add('infoadicional', 'Symfony\Component\Form\Extension\Core\Type\TextareaType',array(
                'label'=>'Comentarios o información adicional que desees proporcionar',
                'required'=>false,
            ))
            ->add('comentarios', 'Symfony\Component\Form\Extension\Core\Type\TextareaType',array(
                'label'=>'Comentarios',
                'required'=>false,
            ))
            ->add('recomendacion', 'Symfony\Component\Form\Extension\Core\Type\TextareaType',array(
                'label'=>'Recomendación',
                'required'=>false,

            ))
            ->add('confirmado', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
                'choices'=>array(
                    true=>'Si',
                    false=>'No'),
                'expanded'=>true,
                'required'=>false,
                'placeholder'=>false,
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RegistroBundle\Entity\Form'
        ));
    }


}
