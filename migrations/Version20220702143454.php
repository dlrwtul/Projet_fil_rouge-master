<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220702143454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD is_alivrer TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE livraison ADD user_id INT DEFAULT NULL, ADD etat VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A60C9F1FA76ED395 ON livraison (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP is_alivrer');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FA76ED395');
        $this->addSql('DROP INDEX IDX_A60C9F1FA76ED395 ON livraison');
        $this->addSql('ALTER TABLE livraison DROP user_id, DROP etat');
    }
}
