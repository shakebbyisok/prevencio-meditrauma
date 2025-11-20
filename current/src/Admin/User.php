<?php

namespace App\Admin;

use App\Entity\GrupoEmpresa;
use App\Entity\PrivilegioRoles;
use FOS\UserBundle\Model\Group;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Sonata\UserBundle\Form\Type\SecurityRolesType;

final class User extends AbstractAdmin
{

	public function preUpdate($object) {
		parent::preUpdate($object);
		//$this->updateUser($object);
		if($object->getPlainPassword())
		{
			$um = $this->getConfigurationPool()->getContainer()->get('fos_user.user_manager');
			$um->updateCanonicalFields($object);
			$um->updatePassword($object);
		}
	}

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->with('Datos')
		        ->add('username', TextType::class, ['label' => 'Usuario', 'required' => true])
                ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'required' => false,
                    'first_options' => array('label' => 'Contraseña'),
                    'second_options' => array('label' => 'Confirma la contraseña')
                ])
            ->add('email', TextType::class, ['label' => 'Correo electrónico', 'required' => true])
		        ->add('locale', ChoiceType::class, ['choices' => [
			        'Español' => 'es',
		        ],'required' => false, 'label' => 'Idioma'])
	            ->add('rol', EntityType::class, ['label' => 'Privilegio', 'class' => PrivilegioRoles::class, 'required' => true])
	            ->add('enabled', CheckboxType::class, array('required' => false, 'label' => 'Habilitado'))
                ->add('credentialsExpired', CheckboxType::class, array('required' => false, 'label' => 'Credenciales expiradas'))
                ->add('roles', ChoiceType::class, ['choices' => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                ],'expanded' => true, 'multiple' => true, 'required' => true, 'label' => 'Roles'])
	        ->end()
            /*->with('Configuración correo electrónico')
                ->add('mail', TextType::class)
                ->add('passwordMail', TextType::class, ['label' => 'Contraseña', 'required' => false])
                ->add('encriptacionMail', TextType::class, ['label' => 'Tipo de encriptación', 'required' => false])
                ->add('hostMail', TextType::class, ['label' => 'Host', 'required' => false])
                ->add('puertoMail', TextType::class, ['label' => 'Puerto', 'required' => false])
            ->end()*/
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('username', null, ['label' => 'Usuario'])
	        ->add('rol', null, ['label' => 'Privilegio'])
	        ->add('lastLogin', null, ['label' => 'Última conexión']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('username', null, ['label' => 'Usuario'])
	        ->add('rol', null, ['label' => 'Privilegio'])
	        ->add('lastLogin', null, ['label' => 'Última conexión'])->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }

}