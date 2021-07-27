<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210727142342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add table to storage user wishes.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sylius_wish (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, user_id VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_CD891404584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_wish ADD CONSTRAINT FK_CD891404584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE sylius_wish');
    }
}
