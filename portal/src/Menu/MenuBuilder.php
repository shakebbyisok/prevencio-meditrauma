<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function mainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Empresa', ['route' => 'empresa_show']);
        $menu['Empresa']->addChild('Empresa2', ['route' => 'empresa_show']);
        $menu['Empresa']['Empresa2']->addChild('Empresa3', ['route' => 'empresa_show']);

        return $menu;
    }
}