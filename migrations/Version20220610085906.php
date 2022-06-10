<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610085906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, promotion_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo_code (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, kind_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, discount VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, start_at DATETIME DEFAULT NULL, expires_at DATETIME DEFAULT NULL, became_hot_at DATETIME DEFAULT NULL, delivery_fees DOUBLE PRECISION DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, view_count INT DEFAULT NULL, discr VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, INDEX IDX_C11D7DD1F675F31B (author_id), INDEX IDX_C11D7DD130602CA9 (kind_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_kind (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE temperature (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, promotion_id INT NOT NULL, positive TINYINT(1) NOT NULL, INDEX IDX_BE4E2A6CA76ED395 (user_id), INDEX IDX_BE4E2A6C139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD130602CA9 FOREIGN KEY (kind_id) REFERENCES promotion_kind (id)');
        $this->addSql('ALTER TABLE temperature ADD CONSTRAINT FK_BE4E2A6CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE temperature ADD CONSTRAINT FK_BE4E2A6C139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C139DF194');
        $this->addSql('ALTER TABLE temperature DROP FOREIGN KEY FK_BE4E2A6C139DF194');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD130602CA9');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1F675F31B');
        $this->addSql('ALTER TABLE temperature DROP FOREIGN KEY FK_BE4E2A6CA76ED395');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE promo_code');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_kind');
        $this->addSql('DROP TABLE temperature');
        $this->addSql('DROP TABLE user');
    }
}
