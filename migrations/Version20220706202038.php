<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706202038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_boisson_taille DROP quantite, DROP prix, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE commande_boisson_taille ADD CONSTRAINT FK_CB21213BBF396750 FOREIGN KEY (id) REFERENCES commande_produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_burger DROP quantite, DROP prix, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE commande_burger ADD CONSTRAINT FK_EDB7A1D8BF396750 FOREIGN KEY (id) REFERENCES commande_produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_menu DROP quantite, DROP prix, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE commande_menu ADD CONSTRAINT FK_16693B70BF396750 FOREIGN KEY (id) REFERENCES commande_produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_portion_frites DROP quantite, DROP prix, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE commande_portion_frites ADD CONSTRAINT FK_76BD26B8BF396750 FOREIGN KEY (id) REFERENCES commande_produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_produit ADD type VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_boisson_taille DROP FOREIGN KEY FK_CB21213BBF396750');
        $this->addSql('ALTER TABLE commande_boisson_taille ADD quantite INT NOT NULL, ADD prix DOUBLE PRECISION NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE commande_burger DROP FOREIGN KEY FK_EDB7A1D8BF396750');
        $this->addSql('ALTER TABLE commande_burger ADD quantite INT NOT NULL, ADD prix DOUBLE PRECISION NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE commande_menu DROP FOREIGN KEY FK_16693B70BF396750');
        $this->addSql('ALTER TABLE commande_menu ADD quantite INT NOT NULL, ADD prix DOUBLE PRECISION NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE commande_portion_frites DROP FOREIGN KEY FK_76BD26B8BF396750');
        $this->addSql('ALTER TABLE commande_portion_frites ADD quantite INT NOT NULL, ADD prix DOUBLE PRECISION NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE commande_produit DROP type');
    }
}
