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
use Sonata\AdminBundle\Form\Type\ModelType;
final class Causa extends AbstractAdmin
{

    public function __toString(): string
    {
        return $this->getDescripcion();
    }

    protected function configureFormFields(FormMapper $formMapper)
    {


        $formMapper
            ->with('General',  ['class' => 'col-lg-12'])
                ->add('descripcion', TextType::class, ['label' => 'Nombre'])
                ->add('descripcionEs', TextType::class, ['label' => 'Nombre castellano', 'required' => false])
                ->add('descripcionCa', TextType::class, ['label' => 'Nombre catalán', 'required' => false])
                //->add('grupoRiesgo', EntityType::class, ['label' => 'Grupo riesgo', 'class' => \App\Entity\GrupoRiesgo::class, 'required' => false])
                ->add('riesgo', EntityType::class, ['label' => 'Riesgo', 'class' => \App\Entity\Riesgo::class, 'required' => false])
                ->add('anulado', CheckboxType::class, ['label' => 'Anulado', 'required' => false])
            ->end();
            //->with('Restricciones',  ['class' => 'col-lg-12'])
                //->add('restriccionEmbarazada', EntityType::class, ['label' => 'Embarazadas', 'class' => \App\Entity\Restriccion::class, 'required' => false])
                //->add('restriccionMenores', EntityType::class, ['label' => 'Menores', 'class' => \App\Entity\Restriccion::class, 'required' => false])
                //->add('restriccionSensibles', EntityType::class, ['label' => 'Trabajadores sensibles', 'class' => \App\Entity\Restriccion::class, 'required' => false])
            //->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('descripcion', null, ['label' => 'Nombre'])
            ->add('descripcionEs', null, ['label' => 'Nombre castellano'])
            ->add('descripcionCa', null, ['label' => 'Nombre catalán'])
            //->add('grupoRiesgo', null, ['label' => 'Grupo riesgo'])
            ->add('riesgo', null, ['label' => 'Riesgo'])
            //->add('restriccionEmbarazada', null, ['label' => 'Restricción embarazadas'])
            //->add('restriccionMenores', null, ['label' => 'Restricción menores'])
            //->add('restriccionSensibles', null, ['label' => 'Restricción trabajadores sensibles'])
            ->add('anulado', null, ['label' => 'Anulado']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('descripcion', null, ['label' => 'Nombre'])
            //->add('grupoRiesgo', null, ['label' => 'Grupo riesgo'])
            ->add('riesgo', null, ['label' => 'Riesgo'])
            ->add('anulado', null, ['label' => 'Anulado'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}