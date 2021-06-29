<?php

namespace App\Entity;

use App\Repository\AffectationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AffectationRepository::class)
 */
class Affectation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_adherent;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_groupe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAdherent(): ?int
    {
        return $this->id_adherent;
    }

    public function setIdAdherent(int $id_adherent): self
    {
        $this->id_adherent = $id_adherent;

        return $this;
    }

    public function getIdGroupe(): ?int
    {
        return $this->id_groupe;
    }

    public function setIdGroupe(int $id_groupe): self
    {
        $this->id_groupe = $id_groupe;

        return $this;
    }
}
