<?php

namespace App\Entity;

use App\Repository\AlteracionAnaliticaRevisionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlteracionAnaliticaRevisionRepository::class)
 * @Gedmo\Mapping\Annotation\Loggable()
 */
class AlteracionAnaliticaRevision
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AlteracionAnalitica", inversedBy="AlteracionAnaliticaRevision")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $alteracionAnalitica;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Revision", inversedBy="AlteracionAnaliticaRevision")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Mapping\Annotation\Versioned()
     */
    private $revision;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAlteracionAnalitica()
    {
        return $this->alteracionAnalitica;
    }

    /**
     * @param mixed $alteracionAnalitica
     */
    public function setAlteracionAnalitica($alteracionAnalitica): void
    {
        $this->alteracionAnalitica = $alteracionAnalitica;
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
}
