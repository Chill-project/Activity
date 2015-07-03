<?php

/* 
 * Chill is a software for social workers
 * 
 * Copyright (C) 2014-2015, Champs Libres Cooperative SCRLFS, 
 * <http://www.champs-libres.coop>, <info@champs-libres.coop>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
        $this->addSql('CREATE TABLE Activity (id INT NOT NULL, user_id INT DEFAULT NULL, scope_id INT DEFAULT NULL, reason_id INT DEFAULT NULL, type_id INT DEFAULT NULL, person_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, durationTime TIME(0) WITHOUT TIME ZONE NOT NULL, remark TEXT NOT NULL, attendee BOOLEAN, PRIMARY KEY(id))');
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