<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221213083800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carte (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, expansion VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, couleur VARCHAR(255) DEFAULT NULL, cout_carte VARCHAR(255) DEFAULT NULL, artiste VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, atk_def VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carte_collection_carte (carte_id INT NOT NULL, collection_carte_id INT NOT NULL, INDEX IDX_25EAAD63C9C7CEB6 (carte_id), INDEX IDX_25EAAD6338470086 (collection_carte_id), PRIMARY KEY(carte_id, collection_carte_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carte_deck (carte_id INT NOT NULL, deck_id INT NOT NULL, INDEX IDX_E8820FEFC9C7CEB6 (carte_id), INDEX IDX_E8820FEF111948DC (deck_id), PRIMARY KEY(carte_id, deck_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carte_liste_de_souhaits (carte_id INT NOT NULL, liste_de_souhaits_id INT NOT NULL, INDEX IDX_69347F2C9C7CEB6 (carte_id), INDEX IDX_69347F233C53037 (liste_de_souhaits_id), PRIMARY KEY(carte_id, liste_de_souhaits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collection_carte (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_FD2C47AEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, carte_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date_commentaire DATETIME NOT NULL, contenu VARCHAR(255) NOT NULL, INDEX IDX_67F068BCC9C7CEB6 (carte_id), INDEX IDX_67F068BCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deck (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_4FAC3637A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_de_souhaits (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_E39E3F15A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, compte TINYINT(1) NOT NULL, pseudo VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carte_collection_carte ADD CONSTRAINT FK_25EAAD63C9C7CEB6 FOREIGN KEY (carte_id) REFERENCES carte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE carte_collection_carte ADD CONSTRAINT FK_25EAAD6338470086 FOREIGN KEY (collection_carte_id) REFERENCES collection_carte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE carte_deck ADD CONSTRAINT FK_E8820FEFC9C7CEB6 FOREIGN KEY (carte_id) REFERENCES carte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE carte_deck ADD CONSTRAINT FK_E8820FEF111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE carte_liste_de_souhaits ADD CONSTRAINT FK_69347F2C9C7CEB6 FOREIGN KEY (carte_id) REFERENCES carte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE carte_liste_de_souhaits ADD CONSTRAINT FK_69347F233C53037 FOREIGN KEY (liste_de_souhaits_id) REFERENCES liste_de_souhaits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collection_carte ADD CONSTRAINT FK_FD2C47AEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCC9C7CEB6 FOREIGN KEY (carte_id) REFERENCES carte (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE deck ADD CONSTRAINT FK_4FAC3637A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE liste_de_souhaits ADD CONSTRAINT FK_E39E3F15A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carte_collection_carte DROP FOREIGN KEY FK_25EAAD63C9C7CEB6');
        $this->addSql('ALTER TABLE carte_collection_carte DROP FOREIGN KEY FK_25EAAD6338470086');
        $this->addSql('ALTER TABLE carte_deck DROP FOREIGN KEY FK_E8820FEFC9C7CEB6');
        $this->addSql('ALTER TABLE carte_deck DROP FOREIGN KEY FK_E8820FEF111948DC');
        $this->addSql('ALTER TABLE carte_liste_de_souhaits DROP FOREIGN KEY FK_69347F2C9C7CEB6');
        $this->addSql('ALTER TABLE carte_liste_de_souhaits DROP FOREIGN KEY FK_69347F233C53037');
        $this->addSql('ALTER TABLE collection_carte DROP FOREIGN KEY FK_FD2C47AEA76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCC9C7CEB6');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE deck DROP FOREIGN KEY FK_4FAC3637A76ED395');
        $this->addSql('ALTER TABLE liste_de_souhaits DROP FOREIGN KEY FK_E39E3F15A76ED395');
        $this->addSql('DROP TABLE carte');
        $this->addSql('DROP TABLE carte_collection_carte');
        $this->addSql('DROP TABLE carte_deck');
        $this->addSql('DROP TABLE carte_liste_de_souhaits');
        $this->addSql('DROP TABLE collection_carte');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP TABLE liste_de_souhaits');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
