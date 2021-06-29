<?php

namespace App\Entity;

use App\Repository\UtulisateurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UtulisateurRepository::class)
 */
class Utulisateur
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
    private $nom_francais;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_arab;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom_francais;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom_arab;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville_actuel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu_naissance_francais;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu_naissance_arab;

    /**
     * @ORM\Column(type="date")
     */
    private $date_inscription;

    /**
     * @ORM\Column(type="date")
     */
    private $date_naissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cni;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $niveau;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $niveau_deplome;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_cni;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fonction_tuteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tele_tuteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
   

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $allergie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $autre_maladies;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mot_passe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFrancais(): ?string
    {
        return $this->nom_francais;
    }

    public function setNomFrancais(string $nom_francais): self
    {
        $this->nom_francais = $nom_francais;

        return $this;
    }

    public function getNomArab(): ?string
    {
        return $this->nom_arab;
    }

    public function setNomArab(string $nom_arab): self
    {
        $this->nom_arab = $nom_arab;

        return $this;
    }

    public function getPrenomFrancais(): ?string
    {
        return $this->prenom_francais;
    }

    public function setPrenomFrancais(string $prenom_francais): self
    {
        $this->prenom_francais = $prenom_francais;

        return $this;
    }

    public function getPrenomArab(): ?string
    {
        return $this->prenom_arab;
    }

    public function setPrenomArab(string $prenom_arab): self
    {
        $this->prenom_arab = $prenom_arab;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getVilleActuel(): ?string
    {
        return $this->ville_actuel;
    }

    public function setVilleActuel(string $ville_actuel): self
    {
        $this->ville_actuel = $ville_actuel;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getLieuNaissanceFrancais(): ?string
    {
        return $this->lieu_naissance_francais;
    }

    public function setLieuNaissanceFrancais(string $lieu_naissance_francais): self
    {
        $this->lieu_naissance_francais = $lieu_naissance_francais;

        return $this;
    }

    public function getLieuNaissanceArab(): ?string
    {
        return $this->lieu_naissance_arab;
    }

    public function setLieuNaissanceArab(string $lieu_naissance_arab): self
    {
        $this->lieu_naissance_arab = $lieu_naissance_arab;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $date_inscription): self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

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

    public function getCni(): ?string
    {
        return $this->cni;
    }

    public function setCni(string $cni): self
    {
        $this->cni = $cni;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getNiveauDeplome(): ?string
    {
        return $this->niveau_deplome;
    }

    public function setNiveauDeplome(string $niveau_deplome): self
    {
        $this->niveau_deplome = $niveau_deplome;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageCni()
    {
        return $this->image_cni;
    }

    public function setImageCni( $image_cni): self
    {
        $this->image_cni = $image_cni;

        return $this;
    }

    public function getFonctionTuteur(): ?string
    {
        return $this->fonction_tuteur;
    }

    public function setFonctionTuteur(string $fonction_tuteur): self
    {
        $this->fonction_tuteur = $fonction_tuteur;

        return $this;
    }

    public function getTeleTuteur(): ?string
    {
        return $this->tele_tuteur;
    }

    public function setTeleTuteur(string $tele_tuteur): self
    {
        $this->tele_tuteur = $tele_tuteur;

        return $this;
    }

    public function getMotDeàpasse(): ?string
    {
        return $this->mot_deàpasse;
    }

    public function setMotDepasse(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function getAllergie(): ?string
    {
        return $this->allergie;
    }

    public function setAllergie(string $allergie): self
    {
        $this->allergie = $allergie;

        return $this;
    }

    public function getAutreMaladies(): ?string
    {
        return $this->autre_maladies;
    }

    public function setAutreMaladies(string $autre_maladies): self
    {
        $this->autre_maladies = $autre_maladies;

        return $this;
    }

    public function getMotPasse(): ?string
    {
        return $this->mot_passe;
    }

    public function setMotPasse(string $mot_passe): self
    {
        $this->mot_passe = $mot_passe;

        return $this;
    }
}
