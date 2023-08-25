<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825094409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant ADD plant_pot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D723F4FB69C FOREIGN KEY (plant_pot_id) REFERENCES plant_pot (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AB030D723F4FB69C ON plant (plant_pot_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D723F4FB69C');
        $this->addSql('DROP INDEX UNIQ_AB030D723F4FB69C ON plant');
        $this->addSql('ALTER TABLE plant DROP plant_pot_id');
    }
}
