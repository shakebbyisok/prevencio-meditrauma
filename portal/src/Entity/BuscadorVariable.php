<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuscadorVariableRepository")
 */
class BuscadorVariable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\BuscadorTabla", inversedBy="BuscadorVariable")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $tabla;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $campo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $anulado;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tipo;

    public function getId(): ?int
    {
        return $this->id;
    }

	/**
	 * @return mixed
	 */
	public function getTabla() {
		return $this->tabla;
	}

	/**
	 * @param mixed $tabla
	 */
	public function setTabla( $tabla ): void {
		$this->tabla = $tabla;
	}

    public function getCampo(): ?string
    {
        return $this->campo;
    }

    public function setCampo(?string $campo): self
    {
        $this->campo = $campo;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getAnulado(): ?bool
    {
        return $this->anulado;
    }

    public function setAnulado(?bool $anulado): self
    {
        $this->anulado = $anulado;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}
