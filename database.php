DROP TABLE IF EXISTS `Equipment`;
DROP TABLE IF EXISTS `Archer`;
DROP TABLE IF EXISTS `Competition`;
DROP TABLE IF EXISTS `Division`;
DROP TABLE IF EXISTS `Round`;
DROP VIEW IF EXISTS `RoundView`;
DROP TABLE IF EXISTS `Session`;
DROP TABLE IF EXISTS `Staging`;
DROP TABLE IF EXISTS `End`;
DROP TABLE IF EXISTS `Score`;
DROP TABLE IF EXISTS `Ranges`;
DROP TABLE IF EXISTS `Competitive`;

DROP PROCEDURE IF EXISTS `insert_into_score`;

CREATE TABLE `Ranges` (
  `RangeID` VARCHAR(2) NOT NULL,
  `Range` INT NOT NULL,
  PRIMARY KEY (`RangeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Ranges` (`RangeID`, `Range`)
VALUES
('5E', 5),
('6E', 6);


CREATE TABLE `Archer` (
  `ArcherID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `DOB` date NOT NULL,
  `Gender` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `Archer`
  ADD PRIMARY KEY (`ArcherID`);

ALTER TABLE `Archer`
  MODIFY `ArcherID` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `Competition` (
  `CompetitionID` int(11) NOT NULL,
  `CompetitionName` varchar(100) NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `Competition`
  ADD PRIMARY KEY (`CompetitionID`);

ALTER TABLE `Competition`
  MODIFY `CompetitionID` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `Competition` (`CompetitionName`, `StartDate`, `EndDate`)
VALUES
('Arrow Masters Challenge', '2024-06-01', '2024-06-02'),
('Golden Bow Tournament', '2024-07-10', '2024-07-12'),
('Eagle Eye Archery Cup', '2024-08-15', '2024-08-17'),
('Luminous Arrow Championship', '2024-09-05', '2024-09-07'),
('Forest Archer''s Rally', '2024-10-01', '2024-10-03'),
('Shadowstrike Invitational', '2024-11-20', '2024-11-22');


CREATE TABLE `Equipment` (
  `EquipmentID` varchar(10) NOT NULL,
  `Types` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Equipment` (`EquipmentID`, `Types`) VALUES
('C', 'Compound'),
('CB', 'Compound Barebow'),
('L', 'Longbow'),
('R', 'Recurve'),
('RC', 'Recurve Barebow');

ALTER TABLE `Equipment`
  ADD PRIMARY KEY (`EquipmentID`);


CREATE TABLE `Division` (
  `DivisionID` varchar(5) NOT NULL,
  `DivisionName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Division` (`DivisionID`, `DivisionName`) VALUES
('50+F', '50+ Female'),
('50+M', '50+ Male'),
('60+F', '60+ Female'),
('60+M', '60+ Male'),
('70+F', '70+ Female'),
('70+M', '70+ Male'),
('FO', 'Female Open'),
('MO', 'Male Open'),
('U14F', 'Under 14 Female'),
('U14M', 'Under 14 Male'),
('U16F', 'Under 16 Female'),
('U16M', 'Under 16 Male'),
('U18F', 'Under 18 Female'),
('U18M', 'Under 18 Male'),
('U21F', 'Under 21 Female'),
('U21M', 'Under 21 Male');

ALTER TABLE `Division`
  ADD PRIMARY KEY (`DivisionID`);


CREATE TABLE `Staging` (
  `StagingID` INT NOT NULL AUTO_INCREMENT,
  `ArcherID` INT NOT NULL,
  `EquipmentID` VARCHAR(10) NOT NULL,
  `ScoreID` INT NOT NULL,
  `RoundID` INT NOT NULL,
  `RangeID` INT NOT NULL,
  `StageDate` DATE NOT NULL,
  `StageTime` TIME NOT NULL,
  PRIMARY KEY (`StagingID`),
  FOREIGN KEY (`ArcherID`) REFERENCES `Archer`(`ArcherID`),
  FOREIGN KEY (`EquipmentID`) REFERENCES `Equipment`(`EquipmentID`),
  FOREIGN KEY (`ScoreID`) REFERENCES `Score`(`ScoreID`),
  FOREIGN KEY (`RoundID`) REFERENCES `Round`(`RoundID`),
  FOREIGN KEY (`RangeID`) REFERENCES `Ranges`(`RangeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `End` (
  `EndID` INT NOT NULL AUTO_INCREMENT,
  `Arrow1` INT DEFAULT NULL,
  `Arrow2` INT DEFAULT NULL,
  `Arrow3` INT DEFAULT NULL,
  `Arrow4` INT DEFAULT NULL,
  `Arrow5` INT DEFAULT NULL,
  `Arrow6` INT DEFAULT NULL,
  `EndState` BOOLEAN GENERATED ALWAYS AS (
    CASE 
      WHEN Arrow1 IS NOT NULL AND Arrow2 IS NOT NULL AND Arrow3 IS NOT NULL AND Arrow4 IS NOT NULL AND Arrow5 IS NOT NULL AND Arrow6 IS NOT NULL
      THEN TRUE
      ELSE FALSE
    END
  ) VIRTUAL,
  `TotalScore` INT GENERATED ALWAYS AS (
    COALESCE(Arrow1, 0) + COALESCE(Arrow2, 0) + COALESCE(Arrow3, 0) + COALESCE(Arrow4, 0) + COALESCE(Arrow5, 0) + COALESCE(Arrow6, 0)
  ) VIRTUAL,
  PRIMARY KEY (`EndID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `Score` (
  `ScoreID` INT NOT NULL AUTO_INCREMENT,
  `EndID` INT NOT NULL,
  PRIMARY KEY (`ScoreID`),
  FOREIGN KEY (`EndID`) REFERENCES `End`(`EndID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER //
CREATE PROCEDURE insert_into_score (IN p_EndID INT)
BEGIN
  DECLARE end_state BOOLEAN;

  -- Check the EndState of the given EndID
  SELECT `EndState` INTO end_state FROM `End` WHERE `EndID` = p_EndID;

  -- If EndState is TRUE, insert into Score table
  IF end_state THEN
    INSERT INTO `Score` (`EndID`) VALUES (p_EndID);
  ELSE
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot insert EndID with EndState = FALSE into Score table';
  END IF;
END;
//
DELIMITER ;

CREATE TABLE `Round` (
  `RoundID` INT NOT NULL AUTO_INCREMENT,
  `RoundName` VARCHAR(100) NOT NULL,
  `Distance` INT NOT NULL,
  PRIMARY KEY (`RoundID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE VIEW `RoundView` AS
SELECT 
  `RoundID`,
  `RoundName`,
  CONCAT(`Distance`, 'm') AS `Distance`
FROM `Round`;

INSERT INTO `Round` (`RoundName`, `Distance`)
VALUES
('Melbourne', 70),
('Long Melbourne', 30),
('Short Melbourne', 50),
('Sydney', 20),
('Long Sydney', 90),
('Short Sydney', 40),
('Brisbane', 60),
('Long Brisbane', 10),
('Short Brisbane', 20),
('Perth', 90),
('Long Perth', 30),
('Short Perth', 70),
('Adelaide', 40),
('Long Adelaide', 50),
('Short Adelaide', 60),
('Canberra', 10),
('Long Canberra', 20),
('Short Canberra', 90),
('Hobart', 30),
('Long Hobart', 70),
('Short Hobart', 40),
('Darwin', 50),
('Long Darwin', 60),
('Short Darwin', 10);


CREATE TABLE `Competitive` (
  `StagingID` INT NOT NULL,
  `CompetitionID` INT NOT NULL,
  `DivisionID` VARCHAR(5) NOT NULL,
  PRIMARY KEY (`StagingID`, `CompetitionID`, `DivisionID`),
  FOREIGN KEY (`StagingID`) REFERENCES `Staging`(`StagingID`),
  FOREIGN KEY (`CompetitionID`) REFERENCES `Competition`(`CompetitionID`),
  FOREIGN KEY (`DivisionID`) REFERENCES `Division`(`DivisionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

COMMIT;
