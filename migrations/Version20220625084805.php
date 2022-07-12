<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220625084805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE portion_frites ADD menu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE portion_frites ADD CONSTRAINT FK_B3E62962CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_B3E62962CCD7E912 ON portion_frites (menu_id)');
        $this->addSql('ALTER TABLE taille ADD menu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE taille ADD CONSTRAINT FK_76508B38CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_76508B38CCD7E912 ON taille (menu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE portion_frites DROP FOREIGN KEY FK_B3E62962CCD7E912');
        $this->addSql('DROP INDEX IDX_B3E62962CCD7E912 ON portion_frites');
        $this->addSql('ALTER TABLE portion_frites DROP menu_id');
        $this->addSql('ALTER TABLE taille DROP FOREIGN KEY FK_76508B38CCD7E912');
        $this->addSql('DROP INDEX IDX_76508B38CCD7E912 ON taille');
        $this->addSql('ALTER TABLE taille DROP menu_id');
    }
}
