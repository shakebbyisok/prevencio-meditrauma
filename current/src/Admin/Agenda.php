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
use Symfony\Component\Form\Extension\Core\Type\TimeType;

final class Agenda extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('descripcion', TextType::class, ['label' => 'Nombre'])
            ->add('direccion', TextType::class, ['label' => 'Dirección'])
            ->add('horainicio', TimeType::class, ['label' => 'Hora inicio', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('horafin', TimeType::class, ['label' => 'Hora fin', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('duracionTramo', TimeType::class, ['label' => 'Duración tramo', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('finSemanaSn', CheckboxType::class, ['label' => 'Mostrar fin de semana', 'required' => false])
            ->add('anulado', CheckboxType::class, ['label' => 'Anulado', 'required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('descripcion', null, ['label' => 'Nombre'])
            ->add('direccion', null, ['label' => 'Dirección'])
            ->add('horainicio', null, ['label' => 'Hora inicio'])
            ->add('horafin', null, ['label' => 'Hora fin'])
            ->add('duracionTramo', null, ['label' => 'Duración tramo'])
            ->add('finSemanaSn', null, ['label' => 'Mostrar fin de semana'])
		    ->add('anulado', null, ['label' => 'Anulado']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('descripcion', null, ['label' => 'Nombre'])
            ->add('horainicio', null, ['label' => 'Hora inicio'])
            ->add('horafin', null, ['label' => 'Hora fin'])
            ->add('duracionTramo', null, ['label' => 'Duración tramo'])
            ->add('finSemanaSn', null, ['label' => 'Mostrar fin de semana'])
	        ->add('anulado', null, ['label' => 'Anulado'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}