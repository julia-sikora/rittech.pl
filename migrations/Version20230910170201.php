<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910170201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D72A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AB030D72A76ED395 ON plant (user_id)');
        $this->addSql('ALTER TABLE plant_pot ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE plant_pot ADD CONSTRAINT FK_F011B812A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F011B812A76ED395 ON plant_pot (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D72A76ED395');
        $this->addSql('DROP INDEX IDX_AB030D72A76ED395 ON plant');
        $this->addSql('ALTER TABLE plant DROP user_id');
        $this->addSql('ALTER TABLE plant_pot DROP FOREIGN KEY FK_F011B812A76ED395');
        $this->addSql('DROP INDEX IDX_F011B812A76ED395 ON plant_pot');
        $this->addSql('ALTER TABLE plant_pot DROP user_id');
    }
}
