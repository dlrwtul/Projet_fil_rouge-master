<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220628144636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boisson DROP FOREIGN KEY FK_8B97C84D40D9D0AA');
        $this->addSql('DROP INDEX IDX_8B97C84D40D9D0AA ON boisson');
        $this->addSql('ALTER TABLE boisson DROP complement_id');
        $this->addSql('ALTER TABLE portion_frites DROP FOREIGN KEY FK_B3E6296240D9D0AA');
        $this->addSql('DROP INDEX IDX_B3E6296240D9D0AA ON portion_frites');
        $this->addSql('ALTER TABLE portion_frites DROP complement_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boisson ADD complement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE boisson ADD CONSTRAINT FK_8B97C84D40D9D0AA FOREIGN KEY (complement_id) REFERENCES complement (id)');
        $this->addSql('CREATE INDEX IDX_8B97C84D40D9D0AA ON boisson (complement_id)');
        $this->addSql('ALTER TABLE portion_frites ADD complement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE portion_frites ADD CONSTRAINT FK_B3E6296240D9D0AA FOREIGN KEY (complement_id) REFERENCES complement (id)');
        $this->addSql('CREATE INDEX IDX_B3E6296240D9D0AA ON portion_frites (complement_id)');
    }
}
