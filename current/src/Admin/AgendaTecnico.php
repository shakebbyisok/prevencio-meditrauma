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
use App\Entity\User;

final class AgendaTecnico extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('usuario', EntityType::class, ['label' => 'Usuario', 'class' => User::class])
            ->add('horainicio', TimeType::class, ['label' => 'Hora inicio', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('horafin', TimeType::class, ['label' => 'Hora fin', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('duracionTramo', TimeType::class, ['label' => 'Duración tramo', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('finSemanaSn', CheckboxType::class, ['label' => 'Mostrar fin de semana', 'required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('usuario', null, ['label' => 'Usuario'])
            ->add('horainicio', null, ['label' => 'Hora inicio'])
            ->add('horafin', null, ['label' => 'Hora fin'])
            ->add('duracionTramo', null, ['label' => 'Duración tramo'])
            ->add('finSemanaSn', null, ['label' => 'Mostrar fin de semana']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('usuario', null, ['label' => 'Usuario'])
            ->add('horainicio', null, ['label' => 'Hora inicio'])
            ->add('horafin', null, ['label' => 'Hora fin'])
            ->add('duracionTramo', null, ['label' => 'Duración tramo'])
            ->add('finSemanaSn', null, ['label' => 'Mostrar fin de semana'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}