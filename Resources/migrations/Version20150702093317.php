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
class Version20150702093317 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE ActivityReasonCategory DROP COLUMN label;');
        $this->addSql('ALTER TABLE ActivityReasonCategory ADD COLUMN name JSON;');
        $this->addSql('ALTER TABLE ActivityReason DROP COLUMN label;');
        $this->addSql('ALTER TABLE ActivityReason ADD COLUMN name JSON;');
        $this->addSql('ALTER TABLE ActivityType DROP COLUMN name;');
        $this->addSql('ALTER TABLE ActivityType ADD COLUMN name JSON;');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE ActivityReasonCategory DROP COLUMN name;');
        $this->addSql('ALTER TABLE ActivityReasonCategory ADD COLUMN label VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE ActivityReason DROP COLUMN name;');
        $this->addSql('ALTER TABLE ActivityReason ADD COLUMN label VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE ActivityType DROP COLUMN name;');
        $this->addSql('ALTER TABLE ActivityType ADD COLUMN name VARCHAR(255) NOT NULL;');
    }
}