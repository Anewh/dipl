<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230529180205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page ALTER relation TYPE INT');
        $this->addSql('ALTER TABLE page ALTER relation DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER password DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE page ALTER relation TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE page ALTER relation SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER password SET NOT NULL');
    }
}