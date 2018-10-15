CREATE TABLE `Users` (
  `userID` varchar(32) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  `userAuth` varchar(45) NOT NULL,
  `dateCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateLastActive` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sessionID` varchar(32) DEFAULT NULL,
  `userSalt` varchar(64) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userEmail_UNIQUE` (`userEmail`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
