<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200529160810 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE meal ADD id_command_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9C966BE84D FOREIGN KEY (id_command_id) REFERENCES command (id)');
        $this->addSql('CREATE INDEX IDX_9EF68E9C966BE84D ON meal (id_command_id)');
        $this->addSql('ALTER TABLE command ADD id_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8ECAEAD479F37AE5 ON command (id_user_id)');
        $this->addSql('ALTER TABLE user ADD id_restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FCFA10B FOREIGN KEY (id_restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649FCFA10B ON user (id_restaurant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD479F37AE5');
        $this->addSql('DROP INDEX IDX_8ECAEAD479F37AE5 ON command');
        $this->addSql('ALTER TABLE command DROP id_user_id');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9C966BE84D');
        $this->addSql('DROP INDEX IDX_9EF68E9C966BE84D ON meal');
        $this->addSql('ALTER TABLE meal DROP id_command_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FCFA10B');
        $this->addSql('DROP INDEX UNIQ_8D93D649FCFA10B ON user');
        $this->addSql('ALTER TABLE user DROP id_restaurant_id');
    }
}
