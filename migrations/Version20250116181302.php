<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116181302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD street VARCHAR(255) DEFAULT NULL, ADD postal_code VARCHAR(10) DEFAULT NULL, ADD city VARCHAR(100) DEFAULT NULL, DROP adress_client, CHANGE phone phone VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD street VARCHAR(255) DEFAULT NULL, ADD postal_code VARCHAR(10) DEFAULT NULL, ADD city VARCHAR(100) DEFAULT NULL, DROP client_adress');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD adress_client LONGTEXT DEFAULT NULL, DROP street, DROP postal_code, DROP city, CHANGE phone phone VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD client_adress LONGTEXT NOT NULL, DROP street, DROP postal_code, DROP city');
    }
}
