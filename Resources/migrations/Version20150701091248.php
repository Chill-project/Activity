<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150701091248 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE Activity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ActivityReason_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ActivityReasonCategory_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ActivityType_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE Activity (id INT NOT NULL, user_id INT DEFAULT NULL, scope_id INT DEFAULT NULL, reason_id INT DEFAULT NULL, type_id INT DEFAULT NULL, person_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, durationTime TIME(0) WITHOUT TIME ZONE NOT NULL, remark TEXT NOT NULL, attendee BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_55026B0CA76ED395 ON Activity (user_id)');
        $this->addSql('CREATE INDEX IDX_55026B0C682B5931 ON Activity (scope_id)');
        $this->addSql('CREATE INDEX IDX_55026B0C59BB1592 ON Activity (reason_id)');
        $this->addSql('CREATE INDEX IDX_55026B0CC54C8C93 ON Activity (type_id)');
        $this->addSql('CREATE INDEX IDX_55026B0C217BBB47 ON Activity (person_id)');
        $this->addSql('CREATE TABLE ActivityReason (id INT NOT NULL, category_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_654A2FCD12469DE2 ON ActivityReason (category_id)');
        $this->addSql('CREATE TABLE ActivityReasonCategory (id INT NOT NULL, label VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ActivityType (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE Activity ADD CONSTRAINT FK_55026B0CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Activity ADD CONSTRAINT FK_55026B0C682B5931 FOREIGN KEY (scope_id) REFERENCES scopes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Activity ADD CONSTRAINT FK_55026B0C59BB1592 FOREIGN KEY (reason_id) REFERENCES ActivityReason (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Activity ADD CONSTRAINT FK_55026B0CC54C8C93 FOREIGN KEY (type_id) REFERENCES ActivityType (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Activity ADD CONSTRAINT FK_55026B0C217BBB47 FOREIGN KEY (person_id) REFERENCES Person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ActivityReason ADD CONSTRAINT FK_654A2FCD12469DE2 FOREIGN KEY (category_id) REFERENCES ActivityReasonCategory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE Activity DROP CONSTRAINT FK_55026B0C59BB1592');
        $this->addSql('ALTER TABLE ActivityReason DROP CONSTRAINT FK_654A2FCD12469DE2');
        $this->addSql('ALTER TABLE Activity DROP CONSTRAINT FK_55026B0CC54C8C93');
        $this->addSql('DROP SEQUENCE Activity_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ActivityReason_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ActivityReasonCategory_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ActivityType_id_seq CASCADE');
        $this->addSql('DROP TABLE Activity');
        $this->addSql('DROP TABLE ActivityReason');
        $this->addSql('DROP TABLE ActivityReasonCategory');
        $this->addSql('DROP TABLE ActivityType');
    }
}