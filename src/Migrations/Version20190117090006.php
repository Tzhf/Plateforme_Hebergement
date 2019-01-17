<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190117090006 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, code_postal INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gestionnaire CHANGE role role TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE gestionnaire ADD CONSTRAINT FK_F4461B20A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE gestionnaire ADD CONSTRAINT FK_F4461B20D9BB2E9F FOREIGN KEY (dispositif_id) REFERENCES dispositif (id)');
        $this->addSql('ALTER TABLE location ADD date_entree DATE DEFAULT NULL, ADD date_sortie DATE DEFAULT NULL, ADD is_active TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE occupant_id occupant_id INT NOT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB58ABF955 FOREIGN KEY (logement_id) REFERENCES logement (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB67BAA0E5 FOREIGN KEY (occupant_id) REFERENCES occupant (id)');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD4457A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD44576885AC1B FOREIGN KEY (gestionnaire_id) REFERENCES gestionnaire (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gestionnaire DROP FOREIGN KEY FK_F4461B20A73F0036');
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD4457A73F0036');
        $this->addSql('DROP TABLE ville');
        $this->addSql('ALTER TABLE gestionnaire DROP FOREIGN KEY FK_F4461B20D9BB2E9F');
        $this->addSql('ALTER TABLE gestionnaire CHANGE role role TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB58ABF955');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB67BAA0E5');
        $this->addSql('ALTER TABLE location DROP date_entree, DROP date_sortie, DROP is_active, CHANGE occupant_id occupant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD44576885AC1B');
    }
}
