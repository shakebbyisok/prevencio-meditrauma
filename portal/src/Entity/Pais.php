<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaisRepository")
 * @Gedmo\Mapping\Annotation\Loggable()
 */
class Pais
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
	 */
    private $codPais;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
	 */
    private $numDigitosIban;

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

    public function getCodPais(): ?string
    {
        return $this->codPais;
    }

    public function setCodPais(?string $codPais): self
    {
        $this->codPais = $codPais;

        return $this;
    }

    public function getNumDigitosIban(): ?string
    {
        return $this->numDigitosIban;
    }

    public function setNumDigitosIban(?string $numDigitosIban): self
    {
        $this->numDigitosIban = $numDigitosIban;

        return $this;
    }
}
