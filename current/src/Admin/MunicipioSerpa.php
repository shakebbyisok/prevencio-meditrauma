<?php

namespace App\Admin;

use App\Entity\ProvinciaSerpa;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class MunicipioSerpa extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('codMunicipio', TextType::class, ['label' => 'Código de municipio', 'required' => false])
	        ->add('descripcion', TextType::class, ['label' => 'Nombre'])
	        ->add('provincia', EntityType::class, ['label' => 'Provincia', 'class' => ProvinciaSerpa::class, 'required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('codMunicipio', null, ['label' => 'Código de municipio'])
	        ->add('descripcion', null, ['label' => 'Nombre'])
	        ->add('provincia', null, ['label' => 'Provincia']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('descripcion', null, ['label' => 'Nombre'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}