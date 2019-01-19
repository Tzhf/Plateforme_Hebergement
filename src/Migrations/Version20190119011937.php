<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190119011937 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gestionnaire CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE dispositif_id dispositif_id INT DEFAULT NULL, CHANGE role roles TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE location CHANGE date_entree date_entree DATE DEFAULT NULL, CHANGE date_sortie date_sortie DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE logement CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE num_rue num_rue VARCHAR(5) DEFAULT NULL, CHANGE num_bat num_bat VARCHAR(5) DEFAULT NULL, CHANGE num_lgmt num_lgmt VARCHAR(5) DEFAULT NULL, CHANGE etage etage VARCHAR(5) DEFAULT NULL, CHANGE typologie typologie VARCHAR(5) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE occupant CHANGE date_naissance date_naissance DATETIME DEFAULT NULL, CHANGE nb_enfant nb_enfant INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ville CHANGE code_postal code_postal INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gestionnaire CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE dispositif_id dispositif_id INT DEFAULT NULL, CHANGE roles role TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE location CHANGE date_entree date_entree DATE DEFAULT \'NULL\', CHANGE date_sortie date_sortie DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE logement CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE num_rue num_rue VARCHAR(5) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE num_bat num_bat VARCHAR(5) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE num_lgmt num_lgmt VARCHAR(5) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE etage etage VARCHAR(5) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE typologie typologie VARCHAR(5) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE description description VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE occupant CHANGE date_naissance date_naissance DATETIME DEFAULT \'NULL\', CHANGE nb_enfant nb_enfant INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ville CHANGE code_postal code_postal INT DEFAULT NULL');
    }
}
