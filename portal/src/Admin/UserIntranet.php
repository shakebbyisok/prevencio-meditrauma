<?php

namespace App\Admin;

use App\Entity\Empresa;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class UserIntranet extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('usuario', EntityType::class, ['label' => 'Usuario', 'class' => \App\Entity\User::class, 'required' => true])
            ->add('empresa', EntityType::class, ['label' => 'Empresa', 'class' => Empresa::class, 'required' => true])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('usuario', null, ['label' => 'Usuario'])
            ->add('empresa', null, ['label' => 'Empresa']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('usuario', null, ['label' => 'Usuario'])
            ->add('empresa', null, ['label' => 'Empresa'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}