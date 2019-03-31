<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190330151547 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gestionnaire DROP FOREIGN KEY FK_F4461B20D9BB2E9F');
        $this->addSql('ALTER TABLE gestionnaire ADD reset_token VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE gestionnaire ADD CONSTRAINT FK_F4461B20D9BB2E9F FOREIGN KEY (dispositif_id) REFERENCES dispositif (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gestionnaire DROP FOREIGN KEY FK_F4461B20D9BB2E9F');
        $this->addSql('ALTER TABLE gestionnaire DROP reset_token');
        $this->addSql('ALTER TABLE gestionnaire ADD CONSTRAINT FK_F4461B20D9BB2E9F FOREIGN KEY (dispositif_id) REFERENCES dispositif (id) ON DELETE SET NULL');
    }
}
