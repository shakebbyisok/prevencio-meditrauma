<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmpresaModelo347Repository")
 * @Gedmo\Mapping\Annotation\Loggable()
 */
class EmpresaModelo347
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Empresa", inversedBy="EmpresaModelo347")
	 * @ORM\JoinColumn(nullable=true)
	 * @Gedmo\Mapping\Annotation\Versioned()
	 */
	private $empresa;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\GdocFichero", inversedBy="EmpresaModelo347")
	 * @ORM\JoinColumn(nullable=true)
	 * @Gedmo\Mapping\Annotation\Versioned()
	 */
	private $fichero;

    public function getId(): ?int
    {
        return $this->id;
    }

	/**
	 * @return mixed
	 */
	public function getEmpresa() {
		return $this->empresa;
	}

	/**
	 * @param mixed $empresa
	 */
	public function setEmpresa( $empresa ): void {
		$this->empresa = $empresa;
	}

	/**
	 * @return mixed
	 */
	public function getFichero() {
		return $this->fichero;
	}

	/**
	 * @param mixed $fichero
	 */
	public function setFichero( $fichero ): void {
		$this->fichero = $fichero;
	}
}
