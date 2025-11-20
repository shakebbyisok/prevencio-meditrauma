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

final class Prescriptor extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('descripcion', TextType::class, ['label' => 'Nombre'])
	        ->add('contacto', TextType::class, ['label' => 'Contacto', 'required' => false])
	        ->add('direccion', TextType::class, ['label' => 'Dirección', 'required' => false])
	        ->add('localidad', TextType::class, ['label' => 'Localidad', 'required' => false])
	        ->add('provincia', TextType::class, ['label' => 'Provincia', 'required' => false])
	        ->add('codPostal', TextType::class, ['label' => 'Código postal', 'required' => false])
	        ->add('cif', TextType::class, ['label' => 'CIF', 'required' => false])
	        ->add('telefono1', TextType::class, ['label' => 'Teléfono1', 'required' => false])
	        ->add('telefono2', TextType::class, ['label' => 'Teléfono2', 'required' => false])
	        ->add('fax', TextType::class, ['label' => 'FAX', 'required' => false])
	        ->add('mail', TextType::class, ['label' => 'Correo electrónico', 'required' => false])
	        ->add('observaciones', TextareaType::class, ['label' => 'Observaciones', 'required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('descripcion', null, ['label' => 'Nombre'])
		    ->add('contacto', null, ['label' => 'Contacto'])
		    ->add('direccion', null, ['label' => 'Dirección'])
		    ->add('localidad', null, ['label' => 'Localidad'])
		    ->add('provincia', null, ['label' => 'Provincia'])
	        ->add('codPostal', null, ['label' => 'Código postal'])
		    ->add('cif', null, ['label' => 'CIF'])
		    ->add('telefono1', null, ['label' => 'Teléfono1'])
		    ->add('telefono2', null, ['label' => 'Teléfono2'])
		    ->add('fax', null, ['label' => 'FAX'])
		    ->add('mail', null, ['label' => 'Correo electrónico'])
		    ->add('observaciones', null, ['label' => 'Observaciones']);
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