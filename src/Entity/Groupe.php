<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tranche_age;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sexe;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $nb_max_adherent;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $nb_adherent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lib;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrancheAge(): ?string
    {
        return $this->tranche_age;
    }

    public function setTrancheAge(string $tranche_age): self
    {
        $this->tranche_age = $tranche_age;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getNbMaxAdherent()
    {
        return $this->nb_max_adherent;
    }

    public function setNbMaxAdherent( $nb_max_adherent): self
    {
        $this->nb_max_adherent = $nb_max_adherent;

        return $this;
    }

    public function getNbAdherent()
    {
        return $this->nb_adherent;
    }

    public function setNbAdherent( $nb_adherent): self
    {
        $this->nb_adherent = $nb_adherent;

        return $this;
    }

    public function getLib(): ?string
    {
        return $this->lib;
    }

    public function setLib(string $lib): self
    {
        $this->lib = $lib;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
