<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class GdocConfig extends AbstractAdmin
{

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('ruta', TextType::class, ['label' => 'Ruta gestión documental'])
            ->add('url', TextType::class, ['label' => 'Url'])
            ->add('host', TextType::class, ['label' => 'Host'])
            ->add('rutaPortal', TextType::class, ['label' => 'Ruta portal empresa'])
            ->add('carpetaPlantillas', TextType::class, ['label' => 'Carpeta plantillas'])
            ->add('passwordFicherosEncriptados', TextType::class, ['label' => 'Password ficheros encriptados'])
            ->add('carpetaAccidente', TextType::class, ['label' => 'Carpeta accidentes'])
            ->add('carpetaAptitud', TextType::class, ['label' => 'Carpeta aptitudes'])
            ->add('carpetaCertificacion', TextType::class, ['label' => 'Carpeta certificaciones'])
            ->add('carpetaCitacion', TextType::class, ['label' => 'Carpeta citaciones'])
            ->add('carpetaContrato', TextType::class, ['label' => 'Carpeta contratos'])
            ->add('carpetaDocumentoAdjuntoRevision', TextType::class, ['label' => 'Carpeta documentos adjuntos revisión'])
            ->add('carpetaEmpresa', TextType::class, ['label' => 'Carpeta empresa'])
            ->add('carpetaEstudioEpidemiologico', TextType::class, ['label' => 'Carpeta estudios epidemiológicos'])
            ->add('carpetaEvaluacion', TextType::class, ['label' => 'Carpeta evaluaciones'])
            ->add('carpetaFactura', TextType::class, ['label' => 'Carpeta facturas'])
            ->add('carpetaFichaRiesgos', TextType::class, ['label' => 'Carpeta ficha de riesgos'])
            ->add('carpetaGenerada', TextType::class, ['label' => 'Carpeta plantillas generadas'])
            ->add('carpetaManualVs', TextType::class, ['label' => 'Carpeta manual vs'])
            ->add('carpetaModelo347', TextType::class, ['label' => 'Carpeta modelo347'])
            ->add('carpetaMemoria', TextType::class, ['label' => 'Carpeta memorias'])
            ->add('carpetaNotificacion', TextType::class, ['label' => 'Carpeta notificaciones'])
            ->add('carpetaPlanPrevencion', TextType::class, ['label' => 'Carpeta plan de prevención'])
            ->add('carpetaProtocoloAcoso', TextType::class, ['label' => 'Carpeta protocolo acoso'])
            ->add('carpetaResultadoAnaliticaTmp', TextType::class, ['label' => 'Carpeta resultados analiticas temporal'])
            ->add('carpetaRevision', TextType::class, ['label' => 'Carpeta revisiones'])
            ->add('carpetaTemporal', TextType::class, ['label' => 'Carpeta temporal'])
            ->add('carpetaTrabajador', TextType::class, ['label' => 'Carpeta trabajador'])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('ruta', null, ['label' => 'Ruta gestión documental']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('ruta', null, ['label' => 'Ruta gestión documental'])
            ->add('url', null, ['label' => 'Url'])
            ->add('host', null, ['label' => 'Host'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}