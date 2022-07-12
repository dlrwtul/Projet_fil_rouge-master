<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706182643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_boisson_taille ADD menu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande_boisson_taille ADD CONSTRAINT FK_CB21213BCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_CB21213BCCD7E912 ON commande_boisson_taille (menu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_boisson_taille DROP FOREIGN KEY FK_CB21213BCCD7E912');
        $this->addSql('DROP INDEX IDX_CB21213BCCD7E912 ON commande_boisson_taille');
        $this->addSql('ALTER TABLE commande_boisson_taille DROP menu_id');
    }
}
