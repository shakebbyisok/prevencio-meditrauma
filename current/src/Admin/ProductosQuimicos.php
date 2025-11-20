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

final class ProductosQuimicos extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('nombre', TextType::class, ['label' => 'Nombre'])
            ->add('cas', TextType::class, ['label' => 'CAS','required' => false])
            ->add('epis', TextType::class, ['label' => 'EPIS','required' => false])
            ->add('composicion', TextType::class, ['label' => 'Composicion','required' => false])
            ->add('grupoContaminante', EntityType::class, [
                'label' => 'Grupo contaminante',
                'class' => \App\Entity\GrupoContaminante::class,
                'required' => false,
                'choice_label' => 'descripcion', // Cambiar a la propiedad adecuada de GrupoContaminante
            ])
            ->add('grupoContaminante', EntityType::class, ['label' => 'Grupo Contaminante', 'class' => \App\Entity\GrupoContaminante::class,'required' => false])
            ->add('anulado', CheckboxType::class, ['label' => 'Anulado', 'required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('nombre', null, ['label' => 'Nombre'])
            ->add('cas', null, ['label' => 'Cas'])
            ->add('epis', null, ['label' => 'EPIS'])
            ->add('composicion', null, ['label' => 'Composicion'])
            ->add('grupoContaminante', null, ['label' => 'Grupo contaminante'])
            ->add('anulado', null, ['label' => 'Anulado']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('nombre', null, ['label' => 'Nombre'])
            ->add('cas', null, ['label' => 'Cas'])
            ->add('epis', null, ['label' => 'EPIS'])
            ->add('composicion', null, ['label' => 'Composicion'])
            ->add('grupoContaminante', null, ['label' => 'Grupo contaminante'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}