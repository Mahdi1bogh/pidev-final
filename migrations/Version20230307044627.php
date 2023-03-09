<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307044627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, club_id INT DEFAULT NULL, contenu VARCHAR(255) DEFAULT NULL, INDEX IDX_8F91ABF061190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, agent_id INT DEFAULT NULL, terain_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B8EE38723414710B (agent_id), INDEX IDX_B8EE3872BDC9BC7B (terain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, _user_id INT NOT NULL, created_at DATE NOT NULL, INDEX IDX_F5299398D32632E8 (_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, _order_id INT NOT NULL, product_id INT NOT NULL, qty INT NOT NULL, INDEX IDX_52EA1F09A35F2858 (_order_id), INDEX IDX_52EA1F094584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, tournois_id INT DEFAULT NULL, users_id INT DEFAULT NULL, date_p DATETIME NOT NULL, INDEX IDX_D79F6B11752534C (tournois_id), INDEX IDX_D79F6B1167B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, qty INT NOT NULL, img VARCHAR(255) NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, _user_id INT NOT NULL, description VARCHAR(255) NOT NULL, created_at DATE NOT NULL, INDEX IDX_CE606404D32632E8 (_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, description_id INT DEFAULT NULL, created_at DATE NOT NULL, INDEX IDX_5FB6DEC7D9F966B (description_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terrain (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournois (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date_tour DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', image VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, date_fin DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF061190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE38723414710B FOREIGN KEY (agent_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE3872BDC9BC7B FOREIGN KEY (terain_id) REFERENCES terrain (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398D32632E8 FOREIGN KEY (_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09A35F2858 FOREIGN KEY (_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11752534C FOREIGN KEY (tournois_id) REFERENCES tournois (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B1167B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404D32632E8 FOREIGN KEY (_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7D9F966B FOREIGN KEY (description_id) REFERENCES reclamation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF061190A32');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE38723414710B');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE3872BDC9BC7B');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398D32632E8');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09A35F2858');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11752534C');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B1167B3B43D');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404D32632E8');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7D9F966B');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE terrain');
        $this->addSql('DROP TABLE tournois');
    }
}
