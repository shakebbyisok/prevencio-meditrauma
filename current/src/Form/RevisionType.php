<?php

namespace App\Form;

use App\Entity\AlteracionAnalitica;
use App\Entity\AptitudRestriccion;
use App\Entity\Apto;
use App\Entity\Citacion;
use App\Entity\Doctor;
use App\Entity\Empresa;
use App\Entity\EstadoRevision;
use App\Entity\Protocolo;
use App\Entity\PuestoTrabajoCentro;
use App\Entity\Revision;
use App\Entity\Trabajador;
use App\Entity\ValidezAptitud;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RevisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empresa', EntityType::class, ['class' => Empresa::class, 'required' => true, 'disabled' => false, 'data' => $options['empresaObj'], 'choice_label' => 'empresa', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.empresa', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('trabajador', EntityType::class, ['class' => Trabajador::class, 'required' => true, 'disabled' => false, 'data' => $options['trabajadorObj'], 'choice_label' => 'trabajadorDni', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = :anulado')
                    ->andWhere('u.id IN (:id)')
                    ->setParameter('anulado', false)
                    ->setParameter('id', $options['listTrabajadores'])
                    ->orderBy('u.nombre', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('puestoTrabajo', EntityType::class, ['class' => PuestoTrabajoCentro::class, 'required' => true, 'disabled' => false, 'data' => $options['puestoTrabajoObj'], 'choice_label' => 'puestoTrabajoEmpresa', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.id = :id')
                    ->setParameter('id', $options['puestoTrabajoId'])
                    ->orderBy('u.descripcion', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('fecha', DateType::class, ['label' => 'TRANS_FECHA', 'translation_domain' => 'revision', 'required' => true, 'widget' => 'single_text', 'html5' => true])
            ->add('medico', EntityType::class, ['class' => Doctor::class, 'required' => false, 'disabled' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = :anulado')
                    ->setParameter('anulado', false)
                    ->orderBy('u.descripcion', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('apto', EntityType::class, ['class' => Apto::class, 'required' => false, 'disabled' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = :anulado')
                    ->setParameter('anulado', false)
                    ->orderBy('u.descripcion', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('aptitudRestriccion', EntityType::class, ['class' => AptitudRestriccion::class, 'required' => true, 'disabled' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.descripcion', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
//            ->add('restricciones', TextType::class, ['label' => 'TRANS_RESTRICCIONES', 'translation_domain' => 'revision', 'required' => false])
            ->add('fechaCertificacion', DateType::class, ['label' => 'TRANS_FECHA_CERTIFICADO', 'translation_domain' => 'revision', 'required' => false, 'widget' => 'single_text', 'html5' => true])
//            ->add('certificado', CheckboxType::class, ['label' => 'TRANS_CERTIFICADO', 'translation_domain' => 'revision', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
//            ->add('informe', CheckboxType::class, ['label' => 'TRANS_INFORME', 'translation_domain' => 'revision', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('observacionMedico', TextareaType::class, ['label' => 'TRANS_EQUIPO_MEDICO', 'translation_domain' => 'revision', 'required' => false])
            ->add('observacionInterna', TextareaType::class, ['label' => 'TRANS_NOTAS_INTERNAS', 'translation_domain' => 'revision', 'required' => false])
            ->add('observaciones', TextareaType::class, ['label' => 'TRANS_OBSERVACIONES', 'translation_domain' => 'revision', 'required' => false])
            ->add('conclusiones', TextareaType::class, ['label' => 'TRANS_CONCLUSIONES', 'translation_domain' => 'revision', 'required' => false])
            ->add('protocolo', EntityType::class, ['class' => Protocolo::class, 'required' => false, 'disabled' => $options['disabledProtocolosSn'], 'data' => $options['protocolos'], 'expanded'=>true, 'mapped' => false, 'multiple' => true,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false')
                    ->orderBy('u.descripcion', 'ASC');
            },])
            ->add('estado', EntityType::class, ['class' => EstadoRevision::class, 'required' => false, 'disabled' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = :anulado')
                    ->setParameter('anulado', false)
                    ->orderBy('u.descripcion', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('numeroPeticion', TextType::class, ['label' => 'TRANS_NUMERO_PETICION', 'translation_domain' => 'revision', 'required' => false])
            ->add('numeroPeticionVerificacion', TextType::class, ['label' => 'TRANS_NUMERO_PETICION_VERIFICACION', 'translation_domain' => 'revision', 'required' => false])
            ->add('fechaRecuperacionResultado', DateType::class, ['label' => 'TRANS_FECHA_RECUPERACION_RESULTADO', 'translation_domain' => 'revision', 'required' => false, 'disabled' => true, 'widget' => 'single_text', 'html5' => true])
//            ->add('alteracionAnalitica', EntityType::class, ['class' => AlteracionAnalitica::class, 'required' => false, 'disabled' => false, 'data' => $options['alteracionesAnaliticas'], 'expanded'=>true, 'mapped' => false, 'multiple' => true, 'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
//                return $er->createQueryBuilder('u')
//                    ->where('u.anulado = false')
//                    ->orderBy('u.descripcion', 'ASC');
//            },])
            ->add('validez', EntityType::class, ['class' => ValidezAptitud::class, 'required' => false, 'disabled' => false, 'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.descripcion', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('pruebasComplementarias', TextareaType::class, ['label' => 'TRANS_PRUEBAS_COMPLEMENTARIAS', 'translation_domain' => 'citaciones', 'required' => false, 'attr' => ['class' => 'summernote']])
            ->add('telefono', TextType::class, ['label' => 'TRANS_TELEFONO_MOVIL', 'translation_domain' => 'revision', 'required' => true])
            ->add('recomendaciones', TextareaType::class, ['label' => 'TRANS_RECOMENDACIONES', 'translation_domain' => 'revision', 'required' => false])
            ->add('ficheroElectrocardiograma',  FileType::class, ['label' => 'TRANS_ELECTROCARDIOGRAMA', 'translation_domain' => 'revision', 'mapped' => false, 'required' => false])
            ->add('citacion', EntityType::class, ['class' => Citacion::class, 'required' => false, 'disabled' => false, 'data' => $options['citacionObj'], 'choice_label' => 'formatoRevision', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = :anulado')
                    ->andWhere('u.trabajador IN (:trabajador)')
                    ->setParameter('anulado', false)
                    ->setParameter('trabajador', $options['trabajadorId'])
                    ->orderBy('u.fechainicio', 'DESC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('fechaEnvio', DateType::class, ['label' => 'TRANS_FECHA_ENVIO', 'translation_domain' => 'revision', 'required' => false, 'disabled' => true, 'widget' => 'single_text', 'html5' => true])
            ->add('fechaFirma', DateType::class, ['label' => 'TRANS_FECHA_FIRMA', 'translation_domain' => 'revision', 'required' => false, 'disabled' => true, 'widget' => 'single_text', 'html5' => true])
            ->add('dni', TextType::class, ['label' => 'TRANS_DNI', 'translation_domain' => 'trabajadores', 'required' => false, 'mapped' => false])
            ->add('fechaNacimiento', DateType::class, ['label' => 'TRANS_DTNAC', 'translation_domain' => 'trabajadores', 'required' => false, 'mapped' => false, 'widget' => 'single_text', 'html5' => true ])
            ->add('actualizado', CheckboxType::class, ['label' => 'TRANS_ACTUALIZADO', 'translation_domain' => 'puestotrabajoprotocolocuestionario', 'disabled' => $options['disabledProtocolosSn'], 'mapped' => false, 'data' => $options['puestoTrabajoActualizadoSn'], 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Revision::class,
            'trabajadorObj' => null,
            'listTrabajadores' => null,
            'empresaObj' => null,
            'puestoTrabajoObj' => null,
            'empresaId' => null,
            'puestoTrabajoId' => null,
            'protocolos' => null,
            'trabajadorId' => null,
            'citacionObj' => null,
            'puestoTrabajoActualizadoSn' => null,
            'disabledProtocolosSn' => null
        ]);
    }
}
