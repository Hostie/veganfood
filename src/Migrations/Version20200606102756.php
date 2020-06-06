<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200606102756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4FCFA10B');
        $this->addSql('DROP INDEX IDX_8ECAEAD4FCFA10B ON command');
        $this->addSql('ALTER TABLE command CHANGE id_restaurant_id id_restaurant2_id INT NOT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD453049454 FOREIGN KEY (id_restaurant2_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_8ECAEAD453049454 ON command (id_restaurant2_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD453049454');
        $this->addSql('DROP INDEX IDX_8ECAEAD453049454 ON command');
        $this->addSql('ALTER TABLE command CHANGE id_restaurant2_id id_restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4FCFA10B FOREIGN KEY (id_restaurant_id) REFERENCES restaurant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8ECAEAD4FCFA10B ON command (id_restaurant_id)');
    }
}
