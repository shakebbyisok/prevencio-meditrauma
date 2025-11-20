<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuscadorTablaRepository")
 */
class BuscadorTabla
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tabla;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $joinText;

    /**
     * @ORM\Column(type="boolean")
     */
    private $anulado;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTabla(): ?string
    {
        return $this->tabla;
    }

    public function setTabla(?string $tabla): self
    {
        $this->tabla = $tabla;

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

    public function getJoinText(): ?string
    {
        return $this->joinText;
    }

    public function setJoinText(?string $joinText): self
    {
        $this->joinText = $joinText;

        return $this;
    }

    public function getAnulado(): ?bool
    {
        return $this->anulado;
    }

    public function setAnulado(bool $anulado): self
    {
        $this->anulado = $anulado;

        return $this;
    }

	public function __toString() {
		return (String) $this->getAlias();
	}


}
