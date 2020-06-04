<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200604174016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rate (id INT AUTO_INCREMENT NOT NULL, id_meal_id INT NOT NULL, user_id_id INT NOT NULL, note INT DEFAULT NULL, comment LONGTEXT NOT NULL, INDEX IDX_DFEC3F39BD0BCFA6 (id_meal_id), INDEX IDX_DFEC3F399D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meal (id INT AUTO_INCREMENT NOT NULL, id_command_id INT DEFAULT NULL, id_restaurant_id INT NOT NULL, name VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_9EF68E9C966BE84D (id_command_id), INDEX IDX_9EF68E9CFCFA10B (id_restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE command (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, price VARCHAR(10) NOT NULL, date DATE NOT NULL, INDEX IDX_8ECAEAD479F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(70) NOT NULL, address VARCHAR(255) NOT NULL, zipcode VARCHAR(10) NOT NULL, logo VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, category VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, id_restaurant_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, role VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, email VARCHAR(50) NOT NULL, wallet VARCHAR(10) NOT NULL, phone VARCHAR(10) DEFAULT NULL, postal VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(10) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649FCFA10B (id_restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F39BD0BCFA6 FOREIGN KEY (id_meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F399D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9C966BE84D FOREIGN KEY (id_command_id) REFERENCES command (id)');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9CFCFA10B FOREIGN KEY (id_restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FCFA10B FOREIGN KEY (id_restaurant_id) REFERENCES restaurant (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F39BD0BCFA6');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9C966BE84D');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9CFCFA10B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FCFA10B');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F399D86650F');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD479F37AE5');
        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE meal');
        $this->addSql('DROP TABLE command');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE user');
    }
}
