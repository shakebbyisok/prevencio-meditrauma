<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class GdocEmpresaCarpeta extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('nombre', TextType::class, ['label' => 'Nombre', 'required' => true])
            ->add('compartida', CheckboxType::class, ['label' => 'Compartida', 'required' => true])
            ->add('anulado', CheckboxType::class, ['label' => 'Anulado', 'required' => false])

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('nombre', null, ['label' => 'Nombre'])
            ->add('compartida', null, ['label' => 'Compartida'])
            ->add('anulado', null, ['label' => 'Anulado']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('nombre', null, ['label' => 'Nombre'])
	        ->add('anulado', null, ['label' => 'Anulado'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}