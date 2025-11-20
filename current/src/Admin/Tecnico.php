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

final class Tecnico extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('nombre', TextType::class, ['label' => 'Nombre'])
	        ->add('alias', TextType::class, ['label' => 'Alias', 'required' => false])
	        ->add('titulacion', TextType::class, ['label' => 'Titulación', 'required' => false])
	        ->add('especialidad', TextType::class, ['label' => 'Especialidad', 'required' => false])
	        ->add('dni', TextType::class, ['label' => 'DNI', 'required' => false])
	        ->add('porcComision', TextType::class, ['label' => 'Porcentaje comisión', 'required' => false])
            ->add('medico', EntityType::class, ['label' => 'Médico', 'class' => \App\Entity\Doctor::class, 'required' => false])
            ->add('correo', TextType::class, ['label' => 'Email', 'required' => false])
            ->add('agente', CheckboxType::class, ['label' => 'Agente', 'required' => false])
            ->add('administracion', CheckboxType::class, ['label' => 'Administración', 'required' => false])
            ->add('tecnico', CheckboxType::class, ['label' => 'Técnico', 'required' => false])
            ->add('vigilanciaSalud', CheckboxType::class, ['label' => 'Vigilancia de la salud', 'required' => false])
            ->add('gestorAdministrativo', CheckboxType::class, ['label' => 'Gestor administrativo', 'required' => false])
            ->add('due', CheckboxType::class, ['label' => 'DUE', 'required' => false])
	        ->add('anulado', CheckboxType::class, ['label' => 'Anulado', 'required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('nombre', null, ['label' => 'Nombre'])
		    ->add('alias', null, ['label' => 'Alias'])
		    ->add('titulacion', null, ['label' => 'Titulación'])
		    ->add('especialidad', null, ['label' => 'Especialidad'])
		    ->add('dni', null, ['label' => 'DNI'])
		    ->add('porcComision', null, ['label' => 'Porcentaje comisión'])
            ->add('correo', null, ['label' => 'Email'])
            ->add('administracion', null, ['label' => 'Administración'])
            ->add('agente', null, ['label' => 'Agente'])
		    ->add('tecnico', null, ['label' => 'Técnico'])
            ->add('vigilanciaSalud', null, ['label' => 'Vigilancia de la salud'])
            ->add('gestorAdministrativo', null, ['label' => 'Gestor administrativo'])
            ->add('due', null, ['label' => 'DUE'])
            ->add('anulado', null, ['label' => 'Anulado']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('nombre', null, ['label' => 'Nombre'])
            ->add('administracion', null, ['label' => 'Administración'])
            ->add('agente', null, ['label' => 'Agente'])
            ->add('tecnico', null, ['label' => 'Técnico'])
            ->add('vigilanciaSalud', null, ['label' => 'Vigilancia de la salud'])
            ->add('gestorAdministrativo', null, ['label' => 'Gestor administrativo'])
            ->add('due', null, ['label' => 'DUE'])
            ->add('anulado', null, ['label' => 'Anulado'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}