<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707151109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_boisson_taille (id INT NOT NULL, boisson_taille_id INT DEFAULT NULL, INDEX IDX_CB21213B75B6EEA7 (boisson_taille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_burger (id INT NOT NULL, burger_id INT DEFAULT NULL, INDEX IDX_EDB7A1D817CE5090 (burger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_menu (id INT NOT NULL, menu_id INT DEFAULT NULL, INDEX IDX_16693B70CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_menu_boisson_taille (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, menu_id INT DEFAULT NULL, boisson_taille_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_5AF24E3182EA2E54 (commande_id), INDEX IDX_5AF24E31CCD7E912 (menu_id), INDEX IDX_5AF24E3175B6EEA7 (boisson_taille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_portion_frites (id INT NOT NULL, portion_frites_id INT DEFAULT NULL, INDEX IDX_76BD26B8203D026B (portion_frites_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_produit (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_DF1E9E8782EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_boisson_taille ADD CONSTRAINT FK_CB21213B75B6EEA7 FOREIGN KEY (boisson_taille_id) REFERENCES boisson_taille (id)');
        $this->addSql('ALTER TABLE commande_boisson_taille ADD CONSTRAINT FK_CB21213BBF396750 FOREIGN KEY (id) REFERENCES commande_produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_burger ADD CONSTRAINT FK_EDB7A1D817CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id)');
        $this->addSql('ALTER TABLE commande_burger ADD CONSTRAINT FK_EDB7A1D8BF396750 FOREIGN KEY (id) REFERENCES commande_produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_menu ADD CONSTRAINT FK_16693B70CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE commande_menu ADD CONSTRAINT FK_16693B70BF396750 FOREIGN KEY (id) REFERENCES commande_produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_menu_boisson_taille ADD CONSTRAINT FK_5AF24E3182EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_menu_boisson_taille ADD CONSTRAINT FK_5AF24E31CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE commande_menu_boisson_taille ADD CONSTRAINT FK_5AF24E3175B6EEA7 FOREIGN KEY (boisson_taille_id) REFERENCES boisson_taille (id)');
        $this->addSql('ALTER TABLE commande_portion_frites ADD CONSTRAINT FK_76BD26B8203D026B FOREIGN KEY (portion_frites_id) REFERENCES portion_frites (id)');
        $this->addSql('ALTER TABLE commande_portion_frites ADD CONSTRAINT FK_76BD26B8BF396750 FOREIGN KEY (id) REFERENCES commande_produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E8782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_boisson_taille DROP FOREIGN KEY FK_CB21213BBF396750');
        $this->addSql('ALTER TABLE commande_burger DROP FOREIGN KEY FK_EDB7A1D8BF396750');
        $this->addSql('ALTER TABLE commande_menu DROP FOREIGN KEY FK_16693B70BF396750');
        $this->addSql('ALTER TABLE commande_portion_frites DROP FOREIGN KEY FK_76BD26B8BF396750');
        $this->addSql('DROP TABLE commande_boisson_taille');
        $this->addSql('DROP TABLE commande_burger');
        $this->addSql('DROP TABLE commande_menu');
        $this->addSql('DROP TABLE commande_menu_boisson_taille');
        $this->addSql('DROP TABLE commande_portion_frites');
        $this->addSql('DROP TABLE commande_produit');
    }
}
