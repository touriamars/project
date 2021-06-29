<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
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
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lib;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_ttc;

    /**
     * @ORM\Column(type="float")
     */
    private $tva;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_fournisseur;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock_alerte;

    /**
     * @ORM\Column(type="float")
     */
    private $fraix_stockage;

    /**
     * @ORM\Column(type="float")
     */
    private $poid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_ht;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixTtc(): ?float
    {
        return $this->prix_ttc;
    }

    public function setPrixTtc(float $prix_ttc): self
    {
        $this->prix_ttc = $prix_ttc;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getIdFournisseur(): ?int
    {
        return $this->id_fournisseur;
    }

    public function setIdFournisseur(int $id_fournisseur): self
    {
        $this->id_fournisseur = $id_fournisseur;

        return $this;
    }

    public function getStockMax(): ?int
    {
        return $this->stock_max;
    }

    public function setStockMax(int $stock_max): self
    {
        $this->stock_max = $stock_max;

        return $this;
    }

    public function getStockAlerte(): ?int
    {
        return $this->stock_alerte;
    }

    public function setStockAlerte(int $stock_alerte): self
    {
        $this->stock_alerte = $stock_alerte;

        return $this;
    }

    public function getFraixStockage(): ?float
    {
        return $this->fraix_stockage;
    }

    public function setFraixStockage(float $fraix_stockage): self
    {
        $this->fraix_stockage = $fraix_stockage;

        return $this;
    }

    public function getPoid(): ?float
    {
        return $this->poid;
    }

    public function setPoid(float $poid): self
    {
        $this->poid = $poid;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getPrixHt(): ?float
    {
        return $this->prix_ht;
    }

    public function setPrixHt(float $prix_ht): self
    {
        $this->prix_ht = $prix_ht;

        return $this;
    }
}
