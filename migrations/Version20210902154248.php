<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210902154248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_845CA2C151147ADE ON order_details');
        $this->addSql('ALTER TABLE order_details CHANGE order_user_id order_shop_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1BB6C6D96 FOREIGN KEY (order_shop_id) REFERENCES order_shop (id)');
        $this->addSql('CREATE INDEX IDX_845CA2C1BB6C6D96 ON order_details (order_shop_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1BB6C6D96');
        $this->addSql('DROP INDEX IDX_845CA2C1BB6C6D96 ON order_details');
        $this->addSql('ALTER TABLE order_details CHANGE order_shop_id order_user_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_845CA2C151147ADE ON order_details (order_user_id)');
    }
}
