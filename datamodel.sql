-- Database tables

-- Table structure for table `user`
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `firstName` varchar(40) NOT NULL,
  `lastName` varchar(40) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `passHash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`email`)
);

-- Table structure for table `store`
CREATE TABLE `store` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 0,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `image` varchar(2048) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Table structure for table `review`
CREATE TABLE `review` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  `storeId` int NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `reviewText` text DEFAULT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`storeId`) REFERENCES `store` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);