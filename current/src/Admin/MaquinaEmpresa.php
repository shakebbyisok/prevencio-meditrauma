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

final class MaquinaEmpresa extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('descripcion', TextType::class, ['label' => 'Nombre'])
            ->add('codigo', TextType::class, ['label' => 'Código','required' => false])
            ->add('fabricante', TextType::class, ['label' => 'Fabricante','required' => false])
            ->add('modelo', TextType::class, ['label' => 'Modelo','required' => false])
            ->add('numSerie', TextType::class, ['label' => 'Número de serie','required' => false])
            ->add('anyoFabricacion', TextType::class, ['label' => 'Anyo fabricación','required' => false])
            ->add('anyoCompra', TextType::class, ['label' => 'Anyo compra','required' => false])
            ->add('placaCaracteristica', TextType::class, ['label' => 'Placa caracteristica','required' => false])
            ->add('marcadoCE', TextType::class, ['label' => 'Marcado CE','required' => false])
            ->add('conformidad', CheckboxType::class, ['label' => 'Conformidad', 'required' => false])
            ->add('manualInstrucciones', CheckboxType::class, ['label' => 'Manual de Instrucciones', 'required' => false])
            ->add('observaciones', TextType::class, ['label' => 'Observaciones','required' => false])
            ->add('empresa', EntityType::class, [
                'label' => 'Empresa',
                'class' => \App\Entity\Empresa::class,
                'required' => false,
                'choice_label' => 'Empresa', // Cambiar 'nombre' al campo adecuado de Empresa
            ])
            ->add('grupoMaquina', EntityType::class, ['label' => 'Grupo', 'class' => \App\Entity\GrupoMaquina::class,'required' => false])
            ->add('anulado', CheckboxType::class, ['label' => 'Anulado', 'required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('descripcion', null, ['label' => 'Nombre'])
            ->add('codigo', null, ['label' => 'Código'])

            ->add('fabricante', null, ['label' => 'Fabricante'])
            ->add('modelo', null, ['label' => 'Modelo'])
            ->add('numSerie', null, ['label' => 'Número de serie'])
            ->add('anyoFabricacion', null, ['label' => 'Anyo fabricación'])
            ->add('anyoCompra', null, ['label' => 'Anyo compra'])
            ->add('placaCaracteristica', null, ['label' => 'Placa caracteristica'])
            ->add('marcadoCE', null, ['label' => 'Marcado CE'])
            ->add('conformidad', null, ['label' => 'Conformidad'])
            ->add('manualInstrucciones', null, ['label' => 'Manual de Instrucciones'])
            ->add('observaciones', null, ['label' => 'Observaciones'])
            ->add('grupoMaquina', null, ['label' => 'Grupo'])
            ->add('anulado', null, ['label' => 'Anulado']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('descripcion', null, ['label' => 'Nombre'])
            ->add('codigo', null, ['label' => 'Código'])
            ->add('fabricante', null, ['label' => 'Fabricante'])
            ->add('modelo', null, ['label' => 'Modelo'])
            ->add('numSerie', null, ['label' => 'Número de serie'])
            ->add('anyoFabricacion', null, ['label' => 'Anyo fabricación'])
            ->add('anyoCompra', null, ['label' => 'Anyo compra'])
            ->add('placaCaracteristica', null, ['label' => 'Placa caracteristica'])
            ->add('marcadoCE', null, ['label' => 'Marcado CE'])
            ->add('conformidad', null, ['label' => 'Conformidad'])
            ->add('manualInstrucciones', null, ['label' => 'Manual de Instrucciones'])
            ->add('observaciones', null, ['label' => 'Observaciones'])
            ->add('empresa', null, ['label' => 'Empresa'])
            ->add('grupoMaquina', null, ['label' => 'Grupo'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}