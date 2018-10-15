
CREATE TABLE `Template_PollFormat` (
  `questionID` int(11) NOT NULL AUTO_INCREMENT,
  `questionText` text NOT NULL,
  `responseText1` text NOT NULL,
  `responseText2` text NOT NULL,
  `responseText3` text,
  `responseText4` text,
  `responseText5` text,
  `responseText6` text,
  `responseText7` text,
  `responseText8` text,
  `responseText9` text,
  `responseText10` text,
  `responseText11` text,
  `responseText12` text,
  `responseText13` text,
  `responseText14` text,
  `responseText15` text,
  `responseText16` text,
  `correctResponse` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`questionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 MAX_ROWS=100;
