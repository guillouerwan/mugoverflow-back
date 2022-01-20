<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120145142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_color (id INT AUTO_INCREMENT NOT NULL, main_hexa VARCHAR(64) NOT NULL, main_color_name VARCHAR(64) NOT NULL, status INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_user (product_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7BF4E84584665A (product_id), INDEX IDX_7BF4E8A76ED395 (user_id), PRIMARY KEY(product_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secondary_color (id INT AUTO_INCREMENT NOT NULL, secondary_hexa VARCHAR(64) NOT NULL, secondary_color_name VARCHAR(64) NOT NULL, status INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(128) NOT NULL, firstname VARCHAR(64) NOT NULL, lastname VARCHAR(64) NOT NULL, password VARCHAR(128) NOT NULL, status LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', role LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_user ADD CONSTRAINT FK_7BF4E84584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_user ADD CONSTRAINT FK_7BF4E8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD main_color_id INT NOT NULL, ADD secondary_color_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA3F80EC0 FOREIGN KEY (main_color_id) REFERENCES main_color (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADE17BC3F3 FOREIGN KEY (secondary_color_id) REFERENCES secondary_color (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADA3F80EC0 ON product (main_color_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADE17BC3F3 ON product (secondary_color_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA3F80EC0');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADE17BC3F3');
        $this->addSql('ALTER TABLE product_user DROP FOREIGN KEY FK_7BF4E8A76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE main_color');
        $this->addSql('DROP TABLE product_user');
        $this->addSql('DROP TABLE promo');
        $this->addSql('DROP TABLE secondary_color');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_D34A04ADA3F80EC0 ON product');
        $this->addSql('DROP INDEX IDX_D34A04ADE17BC3F3 ON product');
        $this->addSql('ALTER TABLE product DROP main_color_id, DROP secondary_color_id');
    }
}
