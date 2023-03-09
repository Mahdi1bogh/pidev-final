<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307045606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398D32632E8');
        $this->addSql('DROP INDEX IDX_F5299398D32632E8 ON `order`');
        $this->addSql('ALTER TABLE `order` ADD totalprice DOUBLE PRECISION NOT NULL, ADD adresse VARCHAR(255) NOT NULL, DROP _user_id, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('DROP INDEX IDX_52EA1F094584665A ON order_item');
        $this->addSql('ALTER TABLE order_item ADD prod_id INT DEFAULT NULL, DROP product_id, CHANGE _order_id _order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F091C83F75 FOREIGN KEY (prod_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F091C83F75 ON order_item (prod_id)');
        $this->addSql('ALTER TABLE product ADD rating INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F091C83F75');
        $this->addSql('DROP INDEX IDX_52EA1F091C83F75 ON order_item');
        $this->addSql('ALTER TABLE order_item ADD product_id INT NOT NULL, DROP prod_id, CHANGE _order_id _order_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_52EA1F094584665A ON order_item (product_id)');
        $this->addSql('ALTER TABLE product DROP rating, CHANGE category_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD _user_id INT NOT NULL, DROP totalprice, DROP adresse, CHANGE created_at created_at DATE NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398D32632E8 FOREIGN KEY (_user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F5299398D32632E8 ON `order` (_user_id)');
    }
}
