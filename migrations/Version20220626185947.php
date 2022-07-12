<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220626185947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEE8962DA4D60759 ON quartier (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0EBC007A4D60759 ON zone (libelle)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_FEE8962DA4D60759 ON quartier');
        $this->addSql('DROP INDEX UNIQ_A0EBC007A4D60759 ON zone');
    }
}
