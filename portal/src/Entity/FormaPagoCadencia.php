<?php

namespace App\Entity;

use App\Repository\FormaPagoCadenciaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormaPagoCadenciaRepository::class)
 * @Gedmo\Mapping\Annotation\Loggable()
 */
class FormaPagoCadencia
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FormaPago", inversedBy="FormaPagoCadencia")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $formaPago;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $pagos;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $cadencia;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $tipo;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $porcentaje;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFormaPago()
    {
        return $this->formaPago;
    }

    /**
     * @param mixed $formaPago
     */
    public function setFormaPago($formaPago): void
    {
        $this->formaPago = $formaPago;
    }

    /**
     * @return mixed
     */
    public function getPagos()
    {
        return $this->pagos;
    }

    /**
     * @param mixed $pagos
     */
    public function setPagos($pagos): void
    {
        $this->pagos = $pagos;
    }

    /**
     * @return mixed
     */
    public function getCadencia()
    {
        return $this->cadencia;
    }

    /**
     * @param mixed $cadencia
     */
    public function setCadencia($cadencia): void
    {
        $this->cadencia = $cadencia;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param mixed $porcentaje
     */
    public function setPorcentaje($porcentaje): void
    {
        $this->porcentaje = $porcentaje;
    }
}
