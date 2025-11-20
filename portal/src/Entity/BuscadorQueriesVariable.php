<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuscadorQueriesVariableRepository")
 */
class BuscadorQueriesVariable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\BuscadorQueries", inversedBy="BuscadorQueriesVariable")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $query;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\BuscadorVariable", inversedBy="BuscadorQueriesVariable")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $variable;

    public function getId(): ?int
    {
        return $this->id;
    }

	/**
	 * @return mixed
	 */
	public function getQuery() {
		return $this->query;
	}

	/**
	 * @param mixed $query
	 */
	public function setQuery( $query ): void {
		$this->query = $query;
	}

	/**
	 * @return mixed
	 */
	public function getVariable() {
		return $this->variable;
	}

	/**
	 * @param mixed $variable
	 */
	public function setVariable( $variable ): void {
		$this->variable = $variable;
	}
}
