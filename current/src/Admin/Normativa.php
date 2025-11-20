<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class Normativa extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('tituloEs', TextType::class, ['label' => 'Titulo castellano'])
            ->add('tituloCa', TextType::class, ['label' => 'Titulo catalán'])
            ->add('descripcionEs', TextType::class, ['label' => 'Nombre castellano', 'required' => false])
            ->add('descripcionCa', TextType::class, ['label' => 'Nombre catalán', 'required' => false])
            ->add('grupoNormativa', EntityType::class, ['label' => 'Grupo normativa', 'class' => \App\Entity\GrupoNormativa::class, 'required' => false])
            ->add('anulado', CheckboxType::class, ['label' => 'Anulado', 'required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('tituloEs', null, ['label' => 'Titulo castellano'])
            ->add('tituloCa', null, ['label' => 'Titulo catalán'])
            ->add('descripcionEs', null, ['label' => 'Nombre castellano'])
            ->add('descripcionCa', null, ['label' => 'Nombre catalán'])
            ->add('grupoNormativa', null, ['label' => 'Grupo normativa'])
            ->add('anulado', null, ['label' => 'Anulado']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('tituloEs', null, ['label' => 'Titulo'])
            ->add('descripcionEs', null, ['label' => 'Nombre'])
            ->add('grupoNormativa', null, ['label' => 'Grupo normativa'])
	        ->add('descripcion', null, ['label' => 'Nombre'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}