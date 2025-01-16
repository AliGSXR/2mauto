<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250112153916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice_service_option (invoice_id INT NOT NULL, service_option_id INT NOT NULL, INDEX IDX_84C33BEA2989F1FD (invoice_id), INDEX IDX_84C33BEAFF552725 (service_option_id), PRIMARY KEY(invoice_id, service_option_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice_service_option ADD CONSTRAINT FK_84C33BEA2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invoice_service_option ADD CONSTRAINT FK_84C33BEAFF552725 FOREIGN KEY (service_option_id) REFERENCES service_option (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_service_option DROP FOREIGN KEY FK_84C33BEA2989F1FD');
        $this->addSql('ALTER TABLE invoice_service_option DROP FOREIGN KEY FK_84C33BEAFF552725');
        $this->addSql('DROP TABLE invoice_service_option');
    }
}
