<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class Consecuencia extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('descripcion', TextType::class, ['label' => 'Nombre', 'required' => false])
            ->add('metodologia', EntityType::class, ['label' => 'Metodologia', 'class' => \App\Entity\MetodologiaEvaluacion::class, 'required' => false])
            ->add('valor', TextType::class, ['label' => 'Valor', 'required' => false])
            ->add('codigo', TextType::class, ['label' => 'Código', 'required' => false]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('descripcion', null, ['label' => 'Nombre'])
            ->add('metodologia', null, ['label' => 'Metodologia'])
            ->add('valor', null, ['label' => 'Valor'])
            ->add('codigo', null, ['label' => 'Código']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('descripcion', null, ['label' => 'Nombre'])
            ->add('metodologia', null, ['label' => 'Metodologia'])
            ->add('valor', null, ['label' => 'Valor'])
            ->add('codigo', null, ['label' => 'Código'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}