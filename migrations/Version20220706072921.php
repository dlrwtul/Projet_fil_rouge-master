<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706072921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_burger (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, burger_id INT DEFAULT NULL, INDEX IDX_EDB7A1D882EA2E54 (commande_id), INDEX IDX_EDB7A1D817CE5090 (burger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_menu (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, menu_id INT DEFAULT NULL, INDEX IDX_16693B7082EA2E54 (commande_id), INDEX IDX_16693B70CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_portion_frites (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, portion_frites_id INT DEFAULT NULL, INDEX IDX_76BD26B882EA2E54 (commande_id), INDEX IDX_76BD26B8203D026B (portion_frites_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_burger ADD CONSTRAINT FK_EDB7A1D882EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_burger ADD CONSTRAINT FK_EDB7A1D817CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id)');
        $this->addSql('ALTER TABLE commande_menu ADD CONSTRAINT FK_16693B7082EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_menu ADD CONSTRAINT FK_16693B70CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE commande_portion_frites ADD CONSTRAINT FK_76BD26B882EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_portion_frites ADD CONSTRAINT FK_76BD26B8203D026B FOREIGN KEY (portion_frites_id) REFERENCES portion_frites (id)');
        $this->addSql('DROP TABLE taille_boisson');
        $this->addSql('ALTER TABLE boisson_taille DROP INDEX UNIQ_E7A2EE1FF25611A, ADD INDEX IDX_E7A2EE1FF25611A (taille_id)');
        $this->addSql('ALTER TABLE boisson_taille DROP INDEX UNIQ_E7A2EE1734B8089, ADD INDEX IDX_E7A2EE1734B8089 (boisson_id)');
        $this->addSql('ALTER TABLE boisson_taille DROP FOREIGN KEY FK_E7A2EE1A76ED395');
        $this->addSql('DROP INDEX UNIQ_E7A2EE16C6E55B5 ON boisson_taille');
        $this->addSql('DROP INDEX UNIQ_E7A2EE1A76ED395 ON boisson_taille');
        $this->addSql('ALTER TABLE boisson_taille DROP user_id, DROP prix, DROP nom, CHANGE boisson_id boisson_id INT DEFAULT NULL, CHANGE taille_id taille_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD quartier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DDF1E57AB FOREIGN KEY (quartier_id) REFERENCES quartier (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DDF1E57AB ON commande (quartier_id)');
        $this->addSql('ALTER TABLE commande_produit ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2782EA2E54');
        $this->addSql('DROP INDEX IDX_29A5EC2782EA2E54 ON produit');
        $this->addSql('ALTER TABLE produit DROP commande_id');
        $this->addSql('ALTER TABLE taille DROP FOREIGN KEY FK_76508B38CCD7E912');
        $this->addSql('DROP INDEX IDX_76508B38CCD7E912 ON taille');
        $this->addSql('ALTER TABLE taille DROP menu_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE taille_boisson (taille_id INT NOT NULL, boisson_id INT NOT NULL, INDEX IDX_59FAC268FF25611A (taille_id), INDEX IDX_59FAC268734B8089 (boisson_id), PRIMARY KEY(taille_id, boisson_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE taille_boisson ADD CONSTRAINT FK_59FAC268FF25611A FOREIGN KEY (taille_id) REFERENCES taille (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE taille_boisson ADD CONSTRAINT FK_59FAC268734B8089 FOREIGN KEY (boisson_id) REFERENCES boisson (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE commande_burger');
        $this->addSql('DROP TABLE commande_menu');
        $this->addSql('DROP TABLE commande_portion_frites');
        $this->addSql('ALTER TABLE boisson_taille DROP INDEX IDX_E7A2EE1734B8089, ADD UNIQUE INDEX UNIQ_E7A2EE1734B8089 (boisson_id)');
        $this->addSql('ALTER TABLE boisson_taille DROP INDEX IDX_E7A2EE1FF25611A, ADD UNIQUE INDEX UNIQ_E7A2EE1FF25611A (taille_id)');
        $this->addSql('ALTER TABLE boisson_taille ADD user_id INT DEFAULT NULL, ADD prix DOUBLE PRECISION NOT NULL, ADD nom VARCHAR(255) NOT NULL, CHANGE boisson_id boisson_id INT NOT NULL, CHANGE taille_id taille_id INT NOT NULL');
        $this->addSql('ALTER TABLE boisson_taille ADD CONSTRAINT FK_E7A2EE1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E7A2EE16C6E55B5 ON boisson_taille (nom)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E7A2EE1A76ED395 ON boisson_taille (user_id)');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DDF1E57AB');
        $this->addSql('DROP INDEX IDX_6EEAA67DDF1E57AB ON commande');
        $this->addSql('ALTER TABLE commande DROP quartier_id');
        $this->addSql('ALTER TABLE commande_produit DROP type');
        $this->addSql('ALTER TABLE produit ADD commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC2782EA2E54 ON produit (commande_id)');
        $this->addSql('ALTER TABLE taille ADD menu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE taille ADD CONSTRAINT FK_76508B38CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_76508B38CCD7E912 ON taille (menu_id)');
    }
}
