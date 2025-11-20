<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class UsuarioTecnico extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('nombre', TextType::class, ['label' => 'Nombre', 'required' => true])
	        ->add('apellido1', TextType::class, ['label' => 'Primer apellido', 'required' => false])
	        ->add('apellido2', TextType::class, ['label' => 'Segundo apellido', 'required' => false])
            ->add('dni', TextType::class, ['label' => 'DNI', 'required' => false])
            ->add('tecnico', CheckboxType::class, ['label' => 'Técnico', 'required' => false])
            ->add('medico', CheckboxType::class, ['label' => 'Médico', 'required' => false])
            ->add('formador', CheckboxType::class, ['label' => 'Formador', 'required' => false])
            ->add('administrativo', CheckboxType::class, ['label' => 'Administrativo', 'required' => false])
            ->add('numeroColegiado', TextType::class, ['label' => 'Número de colegiado', 'required' => false])
	        ->add('especialidad', TextType::class, ['label' => 'Especialidad', 'required' => false])
	        ->add('observaciones', TextType::class, ['label' => 'Observaciones', 'required' => false])
	        ->add('anulado', CheckboxType::class, ['label' => 'Anulado', 'required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nombre', null, ['label' => 'Nombre'])
            ->add('apellido1', null, ['label' => 'Primer apellido'])
            ->add('apellido2', null, ['label' => 'Segundo apellido'])
            ->add('dni', null, ['label' => 'DNI'])
            ->add('tecnico', null, ['label' => 'Técnico'])
            ->add('medico', null, ['label' => 'Médico'])
            ->add('formador', null, ['label' => 'Formador'])
            ->add('administrativo', null, ['label' => 'Administrativo'])
            ->add('numeroColegiado', null, ['label' => 'Número de colegiado'])
            ->add('especialidad', null, ['label' => 'Especialidad'])
            ->add('observaciones', null, ['label' => 'Observaciones'])
            ->add('anulado', null, ['label' => 'Anulado']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('nombre', null, ['label' => 'Nombre'])
            ->add('apellido1', null, ['label' => 'Primer apellido'])
            ->add('apellido2', null, ['label' => 'Segundo apellido'])
            ->add('tecnico', null, ['label' => 'Técnico'])
            ->add('medico', null, ['label' => 'Médico'])
            ->add('formador', null, ['label' => 'Formador'])
            ->add('administrativo', null, ['label' => 'Administrativo'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}