<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NivelSeguimientoRepository")
 * @Gedmo\Mapping\Annotation\Loggable()
 */
class NivelSeguimiento
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Gedmo\Mapping\Annotation\Versioned()
	 */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
	 */
    private $nivelSeguimiento;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
	 */
    private $descripcion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNivelSeguimiento(): ?string
    {
        return $this->nivelSeguimiento;
    }

    public function setNivelSeguimiento(?string $nivelSeguimiento): self
    {
        $this->nivelSeguimiento = $nivelSeguimiento;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}
