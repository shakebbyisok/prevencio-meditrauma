<?php

namespace App\Form;

use App\Entity\Agenda;
use App\Entity\Citacion;
use App\Entity\Empresa;
use App\Entity\EstadoCitacion;
use App\Entity\User;
use App\Entity\UsuarioTecnico;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CitacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('agenda', EntityType::class, ['class' => Agenda::class, 'required' => true, 'disabled' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
		        return $er->createQueryBuilder('u')
		                  ->where('u.anulado = :anulado')
		                  ->setParameter('anulado', false);
	        }, 'attr' => ['class' => 'select-search']])
	        ->add('fechainicio', DateTimeType::class, ['label' => 'TRANS_FECHA_INICIO', 'translation_domain' => 'citaciones', 'required' => true, 'widget' => 'single_text', 'html5' => true])
            ->add('horainicio', TimeType::class, ['label' => 'TRANS_HORA_LLEGADA', 'translation_domain' => 'citaciones', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('horafin', TimeType::class, ['label' => 'TRANS_HORA_SALIDA', 'translation_domain' => 'citaciones', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('estado', EntityType::class, ['class' => EstadoCitacion::class, 'required' => true, 'disabled' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = :anulado')
                    ->setParameter('anulado', false);
            }, 'attr' => ['class' => 'select-search']])
            ->add('empresa', EntityType::class, ['class' => Empresa::class, 'required' => false, 'disabled' => false, 'choice_label' => 'getEmpresaMedico', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = :anulado')
                    //->andWhere('u.estadoAreaAdministracion = 4 or u.estadoAreaAdministracion is null')
                    ->setParameter('anulado', false)
                    ->orderBy('u.empresa', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('trabajador', ChoiceType::class, ['required' => false, 'disabled' => false, 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('comentarios', TextareaType::class, ['label' => 'TRANS_COMENTARIOS', 'translation_domain' => 'citaciones', 'required' => false])
            ->add('pruebasComplementarias', TextareaType::class, ['label' => 'TRANS_PRUEBAS_COMPLEMENTARIAS', 'translation_domain' => 'citaciones', 'required' => false, 'attr' => ['class' => 'summernote']])
            ->add('usuarioCrea', EntityType::class, ['class' => User::class, 'required' => false, 'disabled' => false, 'choice_label' => 'username', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.enabled = true')
                    ->andWhere('u.rol in (1,4,5,6,9,10)')
                    ->orderBy('u.username', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('tecnico', EntityType::class, ['class' => UsuarioTecnico::class, 'required' => false, 'disabled' => false, 'choice_label' => 'getNombreCompleto', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false')
                    ->andWhere('u.tecnico = true')
                    ->orderBy('u.nombre', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('guardar', SubmitType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'))
	        ->add('eliminar', SubmitType::class, ['attr' => ['class' => 'btn-danger', 'onclick' => 'return confirm(\'¿Desea eliminar?\')']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Citacion::class,
        ]);
    }

    public function onPreSetData(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        if($data->getEmpresa() != null) {
            if($data->getEmpresa()->getEstadoAreaAdministracion() != null) {
                if ($data->getEmpresa()->getEstadoAreaAdministracion()->getId() != 4 and $data->getEmpresa()->getEstadoAreaAdministracion()->getId() != null) {
                    $form->add('guardar', SubmitType::class, array('disabled' => true));
                }
            }
        }
    }
}
