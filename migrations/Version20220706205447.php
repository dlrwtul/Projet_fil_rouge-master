<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706205447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_boisson_taille DROP INDEX UNIQ_CB21213B75B6EEA7, ADD INDEX IDX_CB21213B75B6EEA7 (boisson_taille_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_boisson_taille DROP INDEX IDX_CB21213B75B6EEA7, ADD UNIQUE INDEX UNIQ_CB21213B75B6EEA7 (boisson_taille_id)');
    }
}
