<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormaPagoRepository")
 * @Gedmo\Mapping\Annotation\Loggable()
 */
class FormaPago
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
    private $descripcion;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
     */
	private $formaPagoContable;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return mixed
     */
    public function getFormaPagoContable()
    {
        return $this->formaPagoContable;
    }

    /**
     * @param mixed $formaPagoContable
     */
    public function setFormaPagoContable($formaPagoContable): void
    {
        $this->formaPagoContable = $formaPagoContable;
    }
}
