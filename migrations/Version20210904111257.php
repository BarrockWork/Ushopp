<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210904111257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_shop ADD is_paid TINYINT(1) NOT NULL, ADD reference VARCHAR(255) NOT NULL, CHANGE payment_at payment_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_address ADD company VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_shop DROP is_paid, DROP reference, CHANGE payment_at payment_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user_address DROP company');
    }
}
