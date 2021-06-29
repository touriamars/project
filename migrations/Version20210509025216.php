<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210509025216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utulisateur (id INT AUTO_INCREMENT NOT NULL, nom_francais VARCHAR(255) NOT NULL, nom_arab VARCHAR(255) NOT NULL, prenom_francais VARCHAR(255) NOT NULL, prenom_arab VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, ville_actuel VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, mobile VARCHAR(255) NOT NULL, lieu_naissance_francais VARCHAR(255) NOT NULL, lieu_naissance_arab VARCHAR(255) NOT NULL, date_inscription DATE NOT NULL, date_naissance DATE NOT NULL, sexe VARCHAR(255) NOT NULL, cni VARCHAR(255) NOT NULL, niveau VARCHAR(255) NOT NULL, niveau_deplome VARCHAR(255) NOT NULL, age VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, image_cni VARCHAR(255) NOT NULL, fonction_tuteur VARCHAR(255) NOT NULL, tele_tuteur VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, allergie VARCHAR(255) NOT NULL, autre_maladies VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE utulisateur');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
