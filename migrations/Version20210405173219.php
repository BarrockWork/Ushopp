<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210405173219 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD5FE14CB0');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, status INT NOT NULL, payment_at DATETIME NOT NULL, delivery_address LONGTEXT NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_details (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, shipping_id INT NOT NULL, quantity_product INT NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_845CA2C14584665A (product_id), INDEX IDX_845CA2C14887F3F8 (shipping_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C14584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C14887F3F8 FOREIGN KEY (shipping_id) REFERENCES shipping (id)');
        $this->addSql('DROP TABLE command');
        $this->addSql('DROP TABLE command_detail');
        $this->addSql('DROP INDEX IDX_D34A04AD5FE14CB0 ON product');
        $this->addSql('ALTER TABLE product DROP command_detail_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE command (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, status INT NOT NULL, payment_at DATETIME NOT NULL, delivery_address LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_8ECAEAD4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE command_detail (id INT AUTO_INCREMENT NOT NULL, shipping_id INT NOT NULL, quantity_product INT NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_9145B6D04887F3F8 (shipping_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE command_detail ADD CONSTRAINT FK_9145B6D04887F3F8 FOREIGN KEY (shipping_id) REFERENCES shipping (id)');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('ALTER TABLE product ADD command_detail_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD5FE14CB0 FOREIGN KEY (command_detail_id) REFERENCES command_detail (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD5FE14CB0 ON product (command_detail_id)');
    }
}
