<?php

namespace App\Entity;

use App\Repository\IncidenciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IncidenciaRepository::class)
 */
class Incidencia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $titulo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_creacion;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="id_incidencia")
     */
    private $id_usuario;

    /**
     * @ORM\OneToMany(targetEntity=LineasDeIncidencia::class, mappedBy="incidencia")
     */
    private $id_LineasDeIncidencia;

    /**
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="incidencias")
     */
    private $cliente;

    public function __construct()
    {
        $this->id_LineasDeIncidencia = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fecha_creacion): self
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getIdUsuario(): ?Usuario
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?Usuario $id_usuario): self
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    /**
     * @return Collection|LineasDeIncidencia[]
     */
    public function getIdLineasDeIncidencia(): Collection
    {
        return $this->id_LineasDeIncidencia;
    }

    public function addIdLineasDeIncidencium(LineasDeIncidencia $idLineasDeIncidencium): self
    {
        if (!$this->id_LineasDeIncidencia->contains($idLineasDeIncidencium)) {
            $this->id_LineasDeIncidencia[] = $idLineasDeIncidencium;
            $idLineasDeIncidencium->setIncidencia($this);
        }

        return $this;
    }

    public function removeIdLineasDeIncidencium(LineasDeIncidencia $idLineasDeIncidencium): self
    {
        if ($this->id_LineasDeIncidencia->removeElement($idLineasDeIncidencium)) {
            // set the owning side to null (unless already changed)
            if ($idLineasDeIncidencium->getIncidencia() === $this) {
                $idLineasDeIncidencium->setIncidencia(null);
            }
        }

        return $this;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(?Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }
}
