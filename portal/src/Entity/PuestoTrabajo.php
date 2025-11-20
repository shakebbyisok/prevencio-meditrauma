<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PuestoTrabajoRepository")
 * @Gedmo\Mapping\Annotation\Loggable()
 */
class PuestoTrabajo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Gedmo\Mapping\Annotation\Versioned()
	 */
    private $id;

	/**
	 * @ORM\Column(type="string", length=2000, nullable=true)
	 * @Gedmo\Mapping\Annotation\Versioned()
	 */
	private $descripcion;

    public function getId(): ?int
    {
        return $this->id;
    }

	/**
	 * @return mixed
	 */
	public function getDescripcion() {
		return $this->descripcion;
	}

	/**
	 * @param mixed $descripcion
	 */
	public function setDescripcion( $descripcion ): void {
		$this->descripcion = $descripcion;
	}
}
