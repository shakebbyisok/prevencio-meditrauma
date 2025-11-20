<?php

namespace App\Entity;

use App\Repository\RevisionDocumentoAdjuntoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RevisionDocumentoAdjuntoRepository::class)
 * @Gedmo\Mapping\Annotation\Loggable()
 */
class RevisionDocumentoAdjunto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Revision", inversedBy="RevisionDocumentoAdjunto")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $revision;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $nombre;

    /**
     * @ORM\Column(type="boolean", length=255, nullable=true, options={"default":"true"})
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $mostrar = true;

    /**
     * @ORM\Column(type="boolean", length=255, nullable=true, options={"default":"false"})
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $anulado = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * @param mixed $revision
     */
    public function setRevision($revision): void
    {
        $this->revision = $revision;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getMostrar()
    {
        return $this->mostrar;
    }

    /**
     * @param mixed $mostrar
     */
    public function setMostrar($mostrar): void
    {
        $this->mostrar = $mostrar;
    }

    /**
     * @return mixed
     */
    public function getAnulado()
    {
        return $this->anulado;
    }

    /**
     * @param mixed $anulado
     */
    public function setAnulado($anulado): void
    {
        $this->anulado = $anulado;
    }
}
