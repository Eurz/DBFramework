<?php

namespace Core;

use Exception;
use PDO;
use PDOException;

class DBMaker
{
    private Messages $messageManager;
    private $pdo;
    // private $engine = "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
    private $engine = "";


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


    private function createTableAttributes()
    {

        $query = "DROP TABLE IF EXISTS `attributes`;" . SPACER;
        $query .= "CREATE TABLE IF NOT EXISTS `attributes` (
            `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `title` varchar(60) DEFAULT NULL,
            `type` varchar(120) NOT NULL,
            `createdAt` datetime DEFAULT (now()),
            `attribute` int DEFAULT (NULL)
          )" . SPACER;
        $query .= "$this->engine;";


        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table attributes successfully created');
        }
        return $response;
    }

    private function createTableHidings()
    {

        $query = "DROP TABLE IF EXISTS `hidings`;" . SPACER;
        $query .= "CREATE TABLE IF NOT EXISTS `hidings` (
          `id` int NOT NULL AUTO_INCREMENT,
          `code` varchar(40) NOT NULL,
          `countryId` int NOT NULL,
          `address` varchar(120) NOT NULL,
          `typeId` int NOT NULL,
          PRIMARY KEY (`id`))" . SPACER;
        $query .= "$this->engine;";

        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table hidings successfully created');
        }
        return $response;
    }


    private function createTableMissions()
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
          PRIMARY KEY (`id`))" . SPACER;
        $query .= "$this->engine;";


        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table missions successfully created');
        }
        return $response;
    }


    private function createTableMissionsUsers()
    {

        $query = "DROP TABLE IF EXISTS `missions_users`;" . SPACER;
        $query .= "CREATE TABLE IF NOT EXISTS `missions_users` (
            `user` CHAR(36) NOT NULL,
            `mission` int NOT NULL)" . SPACER;
        $query .= "$this->engine;";


        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table missions user successfully created');
        }
        return $response;
    }


    private function createTableRoles()
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


    private function createTableRolesUsers()
    {

        $query = "DROP TABLE IF EXISTS `roles_users`;" . SPACER;
        $query = "CREATE TABLE IF NOT EXISTS `roles_users` (
          `user` CHAR(36) NOT NULL,
          `role` int NOT NULL)" . SPACER;
        $query .= "$this->engine;";


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
          `createdAt` datetime DEFAULT (now()))" . SPACER;
        $query .= "$this->engine;";


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
          `specialityId` int NOT NULL)" . SPACER;
        $query .= "$this->engine;";

        $response = $this->pdo->exec($query);

        if ($response !== false) {
            $this->messageManager->setSuccess('Table users specialities successfully created');
        }
        return $response;
    }


    private function addTablesConstraints()
    {

        $query = "ALTER TABLE hidings
        ADD CONSTRAINT fk_hiding_country_id FOREIGN KEY (`countryId`) REFERENCES attributes (id) ON DELETE RESTRICT ON UPDATE CASCADE;" . SPACER;

        $query .= "ALTER TABLE missions
        ADD CONSTRAINT `fk_mission_country_id` FOREIGN KEY (`countryId`) REFERENCES `attributes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
        -- ADD CONSTRAINT `fk_mission_hiding_id` FOREIGN KEY (`hidingId`) REFERENCES `hidings` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
        ADD CONSTRAINT `fk_speciality_id` FOREIGN KEY (`specialityId`) REFERENCES `attributes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
        ADD CONSTRAINT `fk_mission_type_id` FOREIGN KEY (`missionTypeId`) REFERENCES `attributes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;" . SPACER;

        $query .= "ALTER TABLE missions_users
        ADD CONSTRAINT `fk_missions_users_mission` FOREIGN KEY (`mission`) REFERENCES `missions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
        ADD CONSTRAINT `fk_missions_users_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;" . SPACER;

        $query .= "ALTER TABLE roles_users
        ADD CONSTRAINT `fk_roles_users_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ,
        ADD CONSTRAINT `fk_roles_users_role` FOREIGN KEY (`role`) REFERENCES `roles` (`id`);" . SPACER;

        // $query .= "ALTER TABLE users
        // ADD CONSTRAINT `fk_users_nationality_id` FOREIGN KEY (`nationalityId`) REFERENCES `attributes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;" . SPACER;

        $query .= "ALTER TABLE userspecialities
        ADD CONSTRAINT `fk_up_specialities` FOREIGN KEY (`specialityId`) REFERENCES `attributes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;" . SPACER;

        $response = $this->pdo->exec($query);

        return $response;
    }

    public function insertData()
    {

        // ATTRIBUTES
        $query = "INSERT INTO `attributes` (`id`, `title`, `type`, `createdAt`, `attribute`)" . SPACER;
        $query .= "VALUES
        (1, 'In preparation for', 'status', '2023-07-24 10:40:42', NULL),
        (2, 'Failure', 'status', '2023-07-24 10:40:42', NULL),
        (3, 'Ended', 'status', '2023-07-24 10:40:54', NULL),
        (4, 'Surveillance', 'missionType', '2023-07-24 10:41:47', NULL),
        (5, 'Assassination', 'missionType', '2023-07-24 10:42:00', NULL),
        (6, 'Infiltration', 'missionType', '2023-07-24 10:42:08', NULL),
        (7, 'Superhero', 'speciality', '2023-07-24 10:42:24', NULL),
        (8, 'Daredevil', 'speciality', '2023-07-20 21:16:31', NULL),
        (9, 'Superninja', 'speciality', '2023-07-19 19:15:01', NULL),
        (10, 'Bombtracker', 'speciality', '2023-07-19 19:15:16', NULL),
        (11, 'Hacking', 'speciality', '2023-07-19 19:15:25', NULL),
        (12, 'Spy', 'speciality', '2023-07-19 19:15:59', NULL),
        (13, 'Villa', 'hiding', '2023-07-19 19:58:52', NULL),
        (14, 'Maison', 'hiding', '2023-07-19 19:59:15', NULL),
        (15, 'Garage', 'hiding', '2023-07-19 19:59:36', NULL),
        (16, 'Tower', 'hiding', '2023-07-20 21:19:36', NULL),
        (17, 'France', 'country', '2023-07-19 19:34:40', NULL),
        (18, 'USA', 'country', '2023-07-19 19:34:49', NULL),
        (19, 'Spain', 'country', '2023-07-19 19:35:00', NULL),
        (20, 'Krypton', 'country', '2023-07-19 19:35:10', NULL),
        (21, 'Marioworld', 'country', '2023-07-19 19:35:22', NULL),
        (22, 'England', 'country', '2023-07-19 20:03:39', NULL),
        (23, 'C-53', 'country', '2023-07-20 21:19:02', NULL),
        (24, 'Nowhere', 'country', '2023-07-20 21:22:02', NULL),
        (50, 'Cave', 'hiding', '2023-07-24 11:05:42', NULL),
        (49, 'SPF (Sans Planète Fixe)', 'nationality', '2023-07-24 10:58:30', 24),
        (48, 'Terranien', 'nationality', '2023-07-24 10:57:54', 23),
        (47, 'English', 'nationality', '2023-07-24 10:57:40', 22),
        (46, 'Mariolandais', 'nationality', '2023-07-24 10:57:28', 21),
        (45, 'Kryptonian', 'nationality', '2023-07-24 10:56:54', 20),
        (44, 'Spanish', 'nationality', '2023-07-24 10:56:22', 19),
        (43, 'American', 'nationality', '2023-07-24 10:55:57', 18),
        (42, 'French', 'nationality', '2023-07-24 10:55:50', 17);" . SPACER;

        // HIDINGS
        $query .= "INSERT INTO `hidings` (`id`, `code`, `countryId`, `address`, `typeId`)" . SPACER;
        $query .= "VALUES
        (1, 'Villa Martin', 17, '2 rue des Martins', 13),
        (2, 'La casa de Papel', 19, '12 rue de la Casa', 13),
        (3, 'Avengers Tower', 18, '15 Hero Street', 16),
        (4, 'Galaxy', 24, 'In space', 14);" . SPACER;

        // MISSIONS
        $query .= "INSERT INTO `missions` (`id`, `title`, `description`, `codeName`, `countryId`, `missionTypeId`, `specialityId`, `startDate`, `endDate`, `status`, `hidingId`)" . SPACER;
        $query .= "VALUES
        (1, 'Kill Thanos', '', 'He\'s not ineluctable', 18, 5, 7, '2023-07-24', '2023-07-30', 1, 3),
        (2, 'Spy justice league', '', 'spy them', 18, 4, 7, '2023-07-24', '2023-07-24', 1, 3),
        (3, 'Explore avenger\'s tower', '', 'WHoe are them?', 18, 6, 7, '2023-07-24', '2023-07-24', 1, 5);" . SPACER;

        // MISSIONS USERS
        $query .= "INSERT INTO `missions_users` (`user`, `mission`)" . SPACER;
        $query .= "VALUES
        ('c14dc03b-2a00-11ee-b234-089798f34b52', 1),
        ('b1860b83-2a00-11ee-b234-089798f34b52', 1),
        ('90285030-2a00-11ee-b234-089798f34b52', 1),
        ('90285030-2a00-11ee-b234-089798f34b52', 2),
        ('b1860b83-2a00-11ee-b234-089798f34b52', 2),
        ('bbd9b2c5-2a01-11ee-b234-089798f34b52', 2),
        ('d6a278bf-2a01-11ee-b234-089798f34b52', 2),
        ('c14dc03b-2a00-11ee-b234-089798f34b52', 3),
        ('bbd9b2c5-2a01-11ee-b234-089798f34b52', 3),
        ('89588785-2a01-11ee-b234-089798f34b52', 3);" . SPACER;


        // ROLES USERS

        $query .= "INSERT INTO `roles_users` (`user`, `role`)" . SPACER;
        $query .= " VALUES
        ('a8e6e8ec-29fd-11ee-b234-089798f34b52', 2);" . SPACER;

        // USERS
        $query .= "INSERT INTO `users` (`id`, `firstName`, `lastName`, `dateOfBirth`, `nationalityId`, `userType`, `identificationCode`, `codeName`, `roles`, `email`, `password`, `createdAt`)" . SPACER;
        $query .= "VALUES
        ('a8e6e8ec-29fd-11ee-b234-089798f34b52', 'Tony', 'Stark', '0000-00-00', 0, 'manager', NULL, NULL, NULL, 'tony@stark.com', '$2y$10\$ebn8LWBoWzixa7QKl2bPXOD6dVBz0EawK/fUAzafwRqb0THnNTv8.', '2023-07-24 10:39:53'),
        ('90285030-2a00-11ee-b234-089798f34b52', 'Steve', 'Rodgers', '1920-07-04', 48, 'agent', 'Captain America', NULL, NULL, 'steve@rogers.com', '$2y$10$7IV2kgnpb2Ovwubi8yDbKOAghRpdxchoRxf7pL79qcQwJSfvgB2L6', '2023-07-24 11:00:40'),
        ('b1860b83-2a00-11ee-b234-089798f34b52', 'Natasha', 'Romanov', '1984-12-03', 43, 'contact', NULL, 'Black Widow', NULL, NULL, NULL, '2023-07-24 11:01:36'),
        ('c14dc03b-2a00-11ee-b234-089798f34b52', 'Than', 'Os', '1900-01-01', 49, 'target', NULL, 'Thanos', NULL, NULL, NULL, '2023-07-24 11:02:02'),
        ('89588785-2a01-11ee-b234-089798f34b52', 'Bruce', 'Wayne', '1915-04-07', 43, 'agent', 'Im not happy', NULL, NULL, 'bruce@wayne.com', '$2y$10\$SMbFO5xCtIacknWhRRvPN.a1zMv0NhwdJUnlAfB35sCgbgxnOP9wa', '2023-07-24 11:07:38'),
        ('bbd9b2c5-2a01-11ee-b234-089798f34b52', 'Alfred', 'Thaddeus Crane Middleton Pennyworth', '1943-04-16', 43, 'contact', NULL, 'I know everything', NULL, NULL, NULL, '2023-07-24 11:09:03'),
        ('d6a278bf-2a01-11ee-b234-089798f34b52', 'Stepen', 'Wolf', '1900-01-01', 49, 'target', NULL, 'Im so bad but unuseful', NULL, NULL, NULL, '2023-07-24 11:09:47'),
        ('f81807d1-2a01-11ee-b234-089798f34b52', 'Clark', 'Kent', '1900-01-01', 45, 'agent', 'Im very very strong', NULL, NULL, 'clark@kent.com', '$2y$10\$eXXFq6WgjcQttMZ0zGBgUeNK1GaVCycxmySaim79z7ZUpRQHC1kcS', '2023-07-24 11:10:44');" . SPACER;

        // USERS'S SPECIALITIES
        $query .= "INSERT INTO `userspecialities` (`userId`, `specialityId`)" . SPACER;
        $query .= "VALUES
        ('90285030-2a00-11ee-b234-089798f34b52', 7),
        ('89588785-2a01-11ee-b234-089798f34b52', 7),
        ('f81807d1-2a01-11ee-b234-089798f34b52', 7);" . SPACER;

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
        $this->createTableUsers();
        $this->createTableRoles();
        $this->createTableMissionsUsers();
        $this->createTableRolesUsers();
        $this->createTableUsersSpecialities();
        $this->insertData();
        $this->addTablesConstraints();
    }
}
