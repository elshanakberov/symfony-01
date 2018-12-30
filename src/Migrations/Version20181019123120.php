<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181019123120 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, micro_post_id INT DEFAULT NULL, liked_by_id INT DEFAULT NULL, seen TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_6000B0D3A76ED395 (user_id), INDEX IDX_6000B0D311E37CEA (micro_post_id), INDEX IDX_6000B0D3B4622EC2 (liked_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D311E37CEA FOREIGN KEY (micro_post_id) REFERENCES micro_post (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3B4622EC2 FOREIGN KEY (liked_by_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE micro_post_backup');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE micro_post_backup (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(280) NOT NULL COLLATE utf8mb4_unicode_ci, time DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_2AEFE017A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE notifications');
    }
}
