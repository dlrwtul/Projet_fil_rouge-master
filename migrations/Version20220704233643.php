<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220704233643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_boisson_taille (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, boisson_taille_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_CB21213B82EA2E54 (commande_id), UNIQUE INDEX UNIQ_CB21213B75B6EEA7 (boisson_taille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_boisson_taille ADD CONSTRAINT FK_CB21213B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_boisson_taille ADD CONSTRAINT FK_CB21213B75B6EEA7 FOREIGN KEY (boisson_taille_id) REFERENCES boisson_taille (id)');
        $this->addSql('ALTER TABLE boisson_taille ADD user_id INT DEFAULT NULL, ADD is_etat TINYINT(1) NOT NULL, ADD nom VARCHAR(255) NOT NULL, CHANGE boisson_id boisson_id INT NOT NULL, CHANGE taille_id taille_id INT NOT NULL');
        $this->addSql('ALTER TABLE boisson_taille ADD CONSTRAINT FK_E7A2EE1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E7A2EE16C6E55B5 ON boisson_taille (nom)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E7A2EE1A76ED395 ON boisson_taille (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commande_boisson_taille');
        $this->addSql('ALTER TABLE boisson_taille DROP FOREIGN KEY FK_E7A2EE1A76ED395');
        $this->addSql('DROP INDEX UNIQ_E7A2EE16C6E55B5 ON boisson_taille');
        $this->addSql('DROP INDEX UNIQ_E7A2EE1A76ED395 ON boisson_taille');
        $this->addSql('ALTER TABLE boisson_taille DROP user_id, DROP is_etat, DROP nom, CHANGE taille_id taille_id INT DEFAULT NULL, CHANGE boisson_id boisson_id INT DEFAULT NULL');
    }
}
