<?php

namespace App\Entity;

use App\Repository\UserIntranetEmpresaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserIntranetEmpresaRepository::class)
 * @Gedmo\Mapping\Annotation\Loggable()
 */
class UserIntranetEmpresa
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Empresa", inversedBy="UserIntranetEmpresa")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $empresa;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserIntranet", inversedBy="UserIntranetEmpresa")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $usuarioIntranet;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param mixed $empresa
     */
    public function setEmpresa($empresa): void
    {
        $this->empresa = $empresa;
    }

    /**
     * @return mixed
     */
    public function getUsuarioIntranet()
    {
        return $this->usuarioIntranet;
    }

    /**
     * @param mixed $usuarioIntranet
     */
    public function setUsuarioIntranet($usuarioIntranet): void
    {
        $this->usuarioIntranet = $usuarioIntranet;
    }
}
