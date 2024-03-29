<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230726190350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page ADD level INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE68C814C7 ON project (code_name)');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN githubname TO github_name');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649444F97DD ON "user" (phone)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE68C814C7');
        $this->addSql('ALTER TABLE page DROP level');
        $this->addSql('DROP INDEX UNIQ_8D93D649444F97DD');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN github_name TO githubname');
    }
}
