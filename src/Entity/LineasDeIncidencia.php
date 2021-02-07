<?php

namespace App\Entity;

use App\Repository\LineasDeIncidenciaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LineasDeIncidenciaRepository::class)
 */
class LineasDeIncidencia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $texto;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_creacion;

    /**
     * @ORM\ManyToOne(targetEntity=Incidencia::class, inversedBy="id_LineasDeIncidencia")
     */
    private $incidencia;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(?string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(?\DateTimeInterface $fecha_creacion): self
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getIncidencia(): ?Incidencia
    {
        return $this->incidencia;
    }

    public function setIncidencia(?Incidencia $incidencia): self
    {
        $this->incidencia = $incidencia;

        return $this;
    }
}
