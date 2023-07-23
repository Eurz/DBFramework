<?php

namespace Core;

use Exception;
use PDO;
use PDOException;

class DBMaker
{
    private Messages $messageManager;
    private $pdo;


    public function __construct()
    {
        $this->messageManager = new Messages();
    }

    /**
     * Create database if not exist
     * @return bool - True if database has been created otherwise false
     */
    public function createDB()
    {
        $dbHost = DBHOST;
        $dbUser = DBUSER;
        $dbPassword = DBPASSWORD;
        $dbName = DBNAME;
        try {
            $dsn = 'mysql:host=' . $dbHost . ';';
            $dbOptions = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ];

            $pdo = new PDO($dsn, $dbUser, $dbPassword, $dbOptions);
            if ($pdo->exec('DROP DATABASE IF EXISTS ' . $dbName . '') !== false) {
                if ($pdo->exec('CREATE DATABASE ' . $dbName) !== false) {
                    $dsn .= 'dbname=' . $dbName;
                    $this->messageManager->setSuccess('Database "<b>' . $dbName . '"</b> successfully created');
                    $this->pdo = new PDO($dsn, $dbUser, $dbPassword, $dbOptions);
                    $this->messageManager->setSuccess('Success');

                    return true;
                } else {
                    $this->messageManager->setError('An error has occurred');

                    return false;
                }
            }
        } catch (PDOException $e) {
            //Gestion de l'erreur de connexion

            $this->messageManager->setError('This database already exist');

            return false;
        }
    }


    public function createTableAttributes()
    {

        $query = "DROP TABLE IF EXISTS `attributes`;" . SPACER;
        $query .= "CREATE TABLE IF NOT EXISTS `attributes` (
            `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `title` varchar(60) DEFAULT NULL,
            `type` varchar(120) NOT NULL,
            `createdAt` datetime DEFAULT (now()),
            `attribute` int DEFAULT (NULL)
          );";

        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table attributes successfully created');
        }
        return $response;
    }

    public function createTableHidings()
    {

        $query = "DROP TABLE IF EXISTS `hidings`;" . SPACER;
        $query .= "CREATE TABLE IF NOT EXISTS `hidings` (
          `id` int NOT NULL AUTO_INCREMENT,
          `code` varchar(40) NOT NULL,
          `countryId` int NOT NULL,
          `address` varchar(120) NOT NULL,
          `typeId` int NOT NULL,
          PRIMARY KEY (`id`),
          CONSTRAINT fk_hiding_country_id FOREIGN KEY (`countryId`) REFERENCES attributes (id) ON DELETE RESTRICT ON UPDATE CASCADE
        );";

        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table hidings successfully created');
        }
        return $response;
    }


    public function createTableMissions()
    {

        $query = "DROP TABLE IF EXISTS `missions`;" . SPACER;
        $query .= "CREATE TABLE IF NOT EXISTS `missions` (
          `id` int NOT NULL AUTO_INCREMENT,
          `title` varchar(255) NOT NULL,
          `description` text,
          `codeName` varchar(60) NOT NULL,
          `countryId` int NOT NULL,
          `missionTypeId` int NOT NULL,
          `specialityId` int NOT NULL,
          `startDate` date NOT NULL,
          `endDate` date NOT NULL,
          `status` int NOT NULL,
          `hidingId` int NOT NULL,
          PRIMARY KEY (`id`),
          CONSTRAINT `fk_mission_country_id` FOREIGN KEY (`countryId`) REFERENCES `attributes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
          CONSTRAINT `fk_mission_hiding_id` FOREIGN KEY (`hidingId`) REFERENCES `hidings` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
          CONSTRAINT `fk_speciality_id` FOREIGN KEY (`specialityId`) REFERENCES `attributes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
          CONSTRAINT `fk_mission_type_id` FOREIGN KEY (`missionTypeId`) REFERENCES `attributes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
        );";

        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table missions successfully created');
        }
        return $response;
    }


    public function createTableMissionsUsers()
    {

        $query = "DROP TABLE IF EXISTS `missions_users`;" . SPACER;
        $query .= "CREATE TABLE IF NOT EXISTS `missions_users` (
            `user` CHAR(36) NOT NULL,
            `mission` int NOT NULL,
            CONSTRAINT `fk_missions_users_mission` FOREIGN KEY (`mission`) REFERENCES `missions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT `fk_missions_users_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
          );";

        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table missions user successfully created');
        }
        return $response;
    }


    public function createTableRoles()
    {

        $query = "DROP TABLE IF EXISTS `roles`;" . SPACER;
        $query .= "CREATE TABLE IF NOT EXISTS `roles` (
          `id` int NOT NULL AUTO_INCREMENT,
          `title` varchar(50) NOT NULL,
          PRIMARY KEY (`id`)
        );" . SPACER;

        $query .= "INSERT INTO `roles` (`id`, `title`) VALUES
        (1, 'ROLE_USER'),
        (2, 'ROLE_ADMIN');";

        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table roles successfully created');
        }
        return $response;
    }


    public function createTableRolesUsers()
    {

        $query = "DROP TABLE IF EXISTS `roles_users`;" . SPACER;
        $query = "CREATE TABLE IF NOT EXISTS `roles_users` (
          `user` CHAR(36) NOT NULL,
          `role` int NOT NULL,
          CONSTRAINT `fk_roles_users_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
          CONSTRAINT `fk_roles_users_role` FOREIGN KEY (`role`) REFERENCES `roles` (`id`)
        );" . SPACER;



        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table roles users successfully created');
        }
        return $response;
    }
    private function createTableUsers()
    {

        $query = "DROP TABLE IF EXISTS `users`;" . SPACER;
        $query = "CREATE TABLE IF NOT EXISTS `users` (
          `id` CHAR(36) NOT NULL PRIMARY KEY,
          `firstName` varchar(60) NOT NULL,
          `lastName` varchar(60) NOT NULL,
          `dateOfBirth` date NOT NULL,
          `nationalityId` int NOT NULL,
          `userType` varchar(60) NOT NULL,
          `identificationCode` varchar(255) DEFAULT NULL,
          `codeName` varchar(255) DEFAULT NULL,
          `roles` json DEFAULT NULL,
          `email` varchar(120) DEFAULT (NULL),
          `password` varchar(60) DEFAULT (NULL),
          `createdAt` datetime DEFAULT (now()),
          CONSTRAINT `fk_users_nationality_id` FOREIGN KEY (`nationalityId`) REFERENCES `attributes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
        );" . SPACER;



        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table users successfully created');
        }
        return $response;
    }


    private function createTableUsersSpecialities()
    {

        $query = "DROP TABLE IF EXISTS `userspecialities`;" . SPACER;
        $query = "CREATE TABLE IF NOT EXISTS `userspecialities` (
          `userId` CHAR(36) NOT NULL,
          `specialityId` int NOT NULL,
          CONSTRAINT `fk_up_specialities` FOREIGN KEY (`specialityId`) REFERENCES `attributes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
        );" . SPACER;



        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table users specialities successfully created');
        }
        return $response;
    }


    public function insertData()
    {

        // ATTRIBUTES
        $query = "INSERT INTO `attributes` (`id`, `title`, `type`, `createdAt`, `attribute`)" . SPACER;
        $query .= "VALUES
        (1, 'In preparation for', 'status', '2023-07-19 19:13:42', NULL),
        (2, 'Failure', 'status', '2023-07-19 19:13:53', NULL),
        (3, 'Ended', 'status', '2023-07-19 19:14:16', NULL),
        (4, 'Superninja', 'speciality', '2023-07-19 19:15:01', NULL),
        (5, 'Bombtracker', 'speciality', '2023-07-19 19:15:16', NULL),
        (6, 'Hacking', 'speciality', '2023-07-19 19:15:25', NULL),
        (7, 'Spy', 'speciality', '2023-07-19 19:15:59', NULL),
        (8, 'Infiltration', 'speciality', '2023-07-19 19:16:24', NULL),
        (10, 'Surveillance', 'missionType', '2023-07-19 19:18:10', NULL),
        (11, 'Assassination', 'missionType', '2023-07-19 19:18:57', NULL),
        (12, 'Infiltration', 'missionType', '2023-07-19 19:19:15', NULL),
        (13, 'France', 'country', '2023-07-19 19:34:40', NULL),
        (14, 'USA', 'country', '2023-07-19 19:34:49', NULL),
        (15, 'Spain', 'country', '2023-07-19 19:35:00', NULL),
        (16, 'Krypton', 'country', '2023-07-19 19:35:10', NULL),
        (17, 'Marioworld', 'country', '2023-07-19 19:35:22', NULL),
        (18, 'Villa', 'hiding', '2023-07-19 19:58:52', NULL),
        (19, 'Maison', 'hiding', '2023-07-19 19:59:15', NULL),
        (20, 'Garage', 'hiding', '2023-07-19 19:59:36', NULL),
        (21, 'Français', 'nationality', '2023-07-19 20:02:32', 13),
        (22, 'Spanish', 'nationality', '2023-07-19 20:02:42', 15),
        (23, 'American', 'nationality', '2023-07-19 20:02:53', 14),
        (24, 'Kryptonian', 'nationality', '2023-07-19 20:03:08', 16),
        (25, 'Marioman', 'nationality', '2023-07-19 20:03:29', 17),
        (26, 'England', 'country', '2023-07-19 20:03:39', NULL),
        (27, 'English', 'nationality', '2023-07-19 20:03:50', 26),
        (28, 'Daredevil', 'speciality', '2023-07-20 21:16:31', NULL),
        (29, 'C-53', 'country', '2023-07-20 21:19:02', NULL),
        (30, 'Terranien', 'nationality', '2023-07-20 21:19:17', 29),
        (31, 'Tower', 'hiding', '2023-07-20 21:19:36', NULL),
        (32, 'Superhero', 'speciality', '2023-07-20 21:21:11', NULL),
        (33, 'Nowhere', 'country', '2023-07-20 21:22:02', NULL),
        (34, 'Nowhereman', 'nationality', '2023-07-20 21:22:22', 33);" . SPACER;

        // HIDINGS
        $query = "INSERT INTO `hidings` (`id`, `code`, `countryId`, `address`, `typeId`)" . SPACER;
        $query .= "VALUES
        (1, 'Villa Martin', 13, '2 reu des Martin', 18),
        (2, 'La casa de Papel', 15, '2 rue de la Casa', 18),
        (3, 'Avengers Tower', 29, 'Los Angeles', 31),
        (4, 'Galaxy', 33, 'In space', 31);" . SPACER;

        // MISSIONS
        $query .= "INSERT INTO `missions` (`id`, `title`, `description`, `codeName`, `countryId`, `missionTypeId`, `specialityId`, `startDate`, `endDate`, `status`, `hidingId`)" . SPACER;
        $query .= "VALUES(30, 'test', '', 'Test', 13, 10, 4, '2023-07-20', '2023-07-20', 1, 1);" . SPACER;

        // MISSIONS USERS
        $query .= "INSERT INTO `missions_users` (`user`, `mission`)" . SPACER;
        $query .= "VALUES
        ('a0919efb-2732-11ee-b234-089798f34b52', 30),
        ('2e7891b5-2681-11ee-b234-089798f34b52', 30),
        ('72c306dc-271a-11ee-b234-089798f34b52', 30),
        ('c0251822-2721-11ee-b234-089798f34b52', 30),
        ('d271c2ab-270a-11ee-b234-089798f34b52', 30);";


        // ROLES USERS

        $query .= "INSERT INTO `roles_users` (`user`, `role`)" . SPACER;
        $query .= " VALUES
        ('143', 1),
        ('143', 2),
        ('33bb0b06-2661-11ee-b234-089798f34b52', 1);" . SPACER;

        // USERS
        $query .= "INSERT INTO `users` (`id`, `firstName`, `lastName`, `dateOfBirth`, `nationalityId`, `userType`, `identificationCode`, `codeName`, `roles`, `email`, `password`, `createdAt`)" . SPACER;
        $query .= "VALUES
        ('0918f0e8-2722-11ee-b234-089798f34b52', 'Georgio', 'Contacto', '2010-10-10', 22, 'contact', NULL, 'Mué bien', NULL, NULL, NULL, '2023-07-20 19:22:43'),
        ('143', 'admin', 'admin', '0000-00-00', 0, 'manager', NULL, NULL, NULL, 'admin@admin.com', '$2y$10$5Wo.lEehzyJab2kN0/7RI.pr920ZGZG.OTqKZm2RySbDHireJe3Qi', '2023-07-09 09:58:36'),
        ('2e7891b5-2681-11ee-b234-089798f34b52', 'Joe', 'la fouine', '2004-04-04', 21, 'agent', 'dddd', NULL, NULL, 'joe@fouine.com', '$2y$10\$cZ3avTIBTk2JloPe5jKHyOMzO7PVO35KYTLga9qOocn.HiFAMuveG', '2023-07-20 00:11:16'),
        ('37f97776-2733-11ee-b234-089798f34b52', 'Nick', 'Fury', '1959-05-23', 30, 'contact', NULL, 'Je suis borgne et alors', NULL, NULL, NULL, '2023-07-20 21:25:43'),
        ('404f1514-271a-11ee-b234-089798f34b52', 'Huggy', 'Les bons tuyaux', '0945-02-13', 23, 'contact', NULL, 'I know everything', NULL, NULL, NULL, '2023-07-20 18:26:59'),
        ('72c306dc-271a-11ee-b234-089798f34b52', 'Bernard', 'Lataupe', '1937-05-26', 21, 'contact', NULL, 'Je suis une taupe', NULL, NULL, NULL, '2023-07-20 18:28:24'),
        ('778cbeba-2719-11ee-b234-089798f34b52', 'James', 'Bond', '1936-04-12', 21, 'agent', 'My name is Bond', NULL, NULL, 'james@bond.com', '$2y$10\$vN8dUEcQCgSvJUXzf7392OEfL7i/.4V53aVvh5etsl8O7QO6jMPoq', '2023-07-20 18:21:22'),
        ('a0919efb-2732-11ee-b234-089798f34b52', 'Iron', 'Man', '1954-01-02', 21, 'agent', 'I\'m Iron Man', NULL, NULL, 'iron@man.com', '$2y$10\$lpPmmtpBo0tJpH8EzKmqg.MhufXGf8W4R2JGF5/J4JziKPE03YnSC', '2023-07-20 21:21:29'),
        ('a7a44ffa-2721-11ee-b234-089798f34b52', 'Uma', 'Turman', '1971-08-21', 23, 'agent', 'I want to kill Bill', NULL, NULL, 'uma@turman.com', '$2y$10$3iPTqF4VRPz2L5Y1YX1Dd.bAfKPHF7pIqS0fyVj4GtsGKU0NJzTE2', '2023-07-20 19:19:59'),
        ('c0251822-2721-11ee-b234-089798f34b52', 'Bill', 'Target', '1923-06-29', 27, 'target', NULL, 'She want to kill me', NULL, NULL, NULL, '2023-07-20 19:20:40'),
        ('d271c2ab-270a-11ee-b234-089798f34b52', 'Jack', 'Target', '1945-12-12', 27, 'target', NULL, 'I\'m not Daniel', NULL, NULL, NULL, '2023-07-20 16:36:32'),
        ('e241652b-2732-11ee-b234-089798f34b52', 'Than', 'Os', '1900-01-01', 34, 'target', NULL, 'Je suis inéluctable', NULL, NULL, NULL, '2023-07-20 21:23:19');" . SPACER;

        // USERS'S SPECIALITIES
        $query .= "INSERT INTO `userspecialities` (`userId`, `specialityId`)" . SPACER;
        $query .= "VALUES
        ('0', 7),
        ('0', 4),
        ('2e7891b5-2681-11ee-b234-089798f34b52', 5),
        ('2e7891b5-2681-11ee-b234-089798f34b52', 4),
        ('2e7891b5-2681-11ee-b234-089798f34b52', 6),
        ('2e7891b5-2681-11ee-b234-089798f34b52', 7),
        ('2e7891b5-2681-11ee-b234-089798f34b52', 8),
        ('2e7891b5-2681-11ee-b234-089798f34b52', 9),
        ('778cbeba-2719-11ee-b234-089798f34b52', 7),
        ('a7a44ffa-2721-11ee-b234-089798f34b52', 4),
        ('a0919efb-2732-11ee-b234-089798f34b52', 32);" . SPACER;

        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Data successfully inserted');
        }
        return $response;
    }


    /**
     * Create table in database and insert default values in
     */
    public function createData()
    {
        $this->createTableAttributes();
        $this->createTableHidings();
        $this->createTableMissions();
        $this->createTableMissionsUsers();
        $this->createTableRoles();
        $this->createTableRolesUsers();
        $this->createTableUsers();
        $this->createTableUsersSpecialities();
        $this->insertData();
    }
}
