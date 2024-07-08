<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240707180300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE feedback_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE resume_id_seq INCREMENT BY 1 MINVALUE 1 START 1');

        $this->addSql('CREATE TABLE company (
            id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            url VARCHAR(255) DEFAULT NULL,
            address VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE resume (
            id INT NOT NULL,
            job_title VARCHAR(255) NOT NULL,
            content TEXT DEFAULT NULL,
            file VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE feedback (
            id INT NOT NULL,
            resume_id INT NOT NULL,
            is_positive BOOLEAN NOT NULL,
            recipient VARCHAR(255) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE INDEX IDX_D2294458D262AF09 ON feedback (resume_id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458D262AF09 FOREIGN KEY (resume_id) REFERENCES resume (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE feedback_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE resume_id_seq CASCADE');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D2294458D262AF09');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE resume');
    }
}
