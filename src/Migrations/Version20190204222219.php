<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190204222219 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dispositif (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gestionnaire (id INT AUTO_INCREMENT NOT NULL, ville_id INT DEFAULT NULL, dispositif_id INT DEFAULT NULL, username VARCHAR(60) NOT NULL, email VARCHAR(60) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', INDEX IDX_F4461B20A73F0036 (ville_id), INDEX IDX_F4461B20D9BB2E9F (dispositif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, logement_id INT NOT NULL, occupant_id INT NOT NULL, date_entree DATE DEFAULT NULL, date_sortie DATE DEFAULT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_5E9E89CB58ABF955 (logement_id), INDEX IDX_5E9E89CB67BAA0E5 (occupant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE logement (id INT AUTO_INCREMENT NOT NULL, ville_id INT DEFAULT NULL, gestionnaire_id INT NOT NULL, num_rue VARCHAR(5) DEFAULT NULL, nom_rue VARCHAR(70) NOT NULL, num_bat VARCHAR(5) DEFAULT NULL, num_lgmt VARCHAR(5) DEFAULT NULL, etage VARCHAR(5) DEFAULT NULL, typologie VARCHAR(5) DEFAULT NULL, capacite INT NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_F0FD4457A73F0036 (ville_id), INDEX IDX_F0FD44576885AC1B (gestionnaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE occupant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(40) NOT NULL, prenom VARCHAR(40) NOT NULL, date_naissance DATETIME DEFAULT NULL, nb_enfant INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, code_postal INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gestionnaire ADD CONSTRAINT FK_F4461B20A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE gestionnaire ADD CONSTRAINT FK_F4461B20D9BB2E9F FOREIGN KEY (dispositif_id) REFERENCES dispositif (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB58ABF955 FOREIGN KEY (logement_id) REFERENCES logement (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB67BAA0E5 FOREIGN KEY (occupant_id) REFERENCES occupant (id)');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD4457A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD44576885AC1B FOREIGN KEY (gestionnaire_id) REFERENCES gestionnaire (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gestionnaire DROP FOREIGN KEY FK_F4461B20D9BB2E9F');
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD44576885AC1B');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB58ABF955');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB67BAA0E5');
        $this->addSql('ALTER TABLE gestionnaire DROP FOREIGN KEY FK_F4461B20A73F0036');
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD4457A73F0036');
        $this->addSql('DROP TABLE dispositif');
        $this->addSql('DROP TABLE gestionnaire');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE logement');
        $this->addSql('DROP TABLE occupant');
        $this->addSql('DROP TABLE ville');
    }
}
