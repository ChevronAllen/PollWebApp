CREATE TABLE `PollsActive` (
  `pollID` varchar(20) NOT NULL,
  `pollCode` varchar(6) DEFAULT NULL,
  `ownerID` varchar(32) DEFAULT NULL,
  `public` tinyint(4) DEFAULT '1',
  `pollName` varchar(255) DEFAULT NULL,
  `dateCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateExpire` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `countQuestions` int(3) NOT NULL DEFAULT '0',
  `dateLastAnswered` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pollID`),
  UNIQUE KEY `pollID_UNIQUE` (`pollID`),
  KEY `pollCode_idx` (`pollCode`),
  KEY `ownerID` (`ownerID`),
  CONSTRAINT `PollsActive_ibfk_1` FOREIGN KEY (`ownerID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
