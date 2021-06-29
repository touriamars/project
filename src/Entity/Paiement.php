<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaiementRepository::class)
 */
class Paiement
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
     * @ORM\Column(type="date")
     */
    private $date_debut;

    /**
     * @ORM\Column(type="date")
     */
    private $date_fin;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant_paye;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_revenu;

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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut( $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin( $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getMontantPaye(): ?int
    {
        return $this->montant_paye;
    }

    public function setMontantPaye(int $montant_paye): self
    {
        $this->montant_paye = $montant_paye;

        return $this;
    }

    public function getTypeRevenu(): ?string
    {
        return $this->type_revenu;
    }

    public function setTypeRevenu(string $type_revenu): self
    {
        $this->type_revenu = $type_revenu;

        return $this;
    }
}
