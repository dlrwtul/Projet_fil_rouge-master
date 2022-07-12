<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707145931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_boisson_taille DROP FOREIGN KEY FK_CB21213BBF396750');
        $this->addSql('ALTER TABLE commande_boisson_taille ADD commande_id INT DEFAULT NULL, ADD quantite INT NOT NULL, ADD prix DOUBLE PRECISION NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE commande_boisson_taille ADD CONSTRAINT FK_CB21213B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_CB21213B82EA2E54 ON commande_boisson_taille (commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_boisson_taille DROP FOREIGN KEY FK_CB21213B82EA2E54');
        $this->addSql('DROP INDEX IDX_CB21213B82EA2E54 ON commande_boisson_taille');
        $this->addSql('ALTER TABLE commande_boisson_taille DROP commande_id, DROP quantite, DROP prix, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE commande_boisson_taille ADD CONSTRAINT FK_CB21213BBF396750 FOREIGN KEY (id) REFERENCES commande_produit (id) ON DELETE CASCADE');
    }
}
