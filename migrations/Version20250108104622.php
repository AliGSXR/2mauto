<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108104622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE galerie_image (id INT AUTO_INCREMENT NOT NULL, section_id INT NOT NULL, image VARCHAR(255) NOT NULL, position INT DEFAULT NULL, INDEX IDX_94BB0BC9D823E37A (section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE galerie_image ADD CONSTRAINT FK_94BB0BC9D823E37A FOREIGN KEY (section_id) REFERENCES galerie_section (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE galerie_image DROP FOREIGN KEY FK_94BB0BC9D823E37A');
        $this->addSql('DROP TABLE galerie_image');
    }
}
