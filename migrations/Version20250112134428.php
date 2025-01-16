<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250112134428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE services_facture DROP FOREIGN KEY FK_177DAFE72989F1FD');
        $this->addSql('DROP TABLE services_facture');
        $this->addSql('ALTER TABLE invoice ADD client_adress LONGTEXT NOT NULL, ADD plaque_immatriculation VARCHAR(50) NOT NULL, ADD tva NUMERIC(10, 0) NOT NULL, DROP plaque_vehicule, CHANGE date date DATETIME NOT NULL, CHANGE details details LONGTEXT NOT NULL, CHANGE total_ht total_htc NUMERIC(10, 0) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE services_facture (id INT AUTO_INCREMENT NOT NULL, invoice_id INT DEFAULT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantity INT NOT NULL, prix_unitaire NUMERIC(10, 0) NOT NULL, INDEX IDX_177DAFE72989F1FD (invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE services_facture ADD CONSTRAINT FK_177DAFE72989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
        $this->addSql('ALTER TABLE invoice ADD plaque_vehicule VARCHAR(255) NOT NULL, ADD total_ht NUMERIC(10, 0) NOT NULL, DROP client_adress, DROP plaque_immatriculation, DROP total_htc, DROP tva, CHANGE date date DATE NOT NULL, CHANGE details details VARCHAR(255) NOT NULL');
    }
}
