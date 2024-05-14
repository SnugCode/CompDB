-- FYI: We use PROCEDURES to simplify entering data into the DB, so the command would be as follows, 
-- CALL archer_insert('Afzaal', 'Hakeem', '1990-01-01', 'Male'); 
-- This line would insert the relevant data into the table when calling the correct procedure name 

CREATE TABLE archer_id_seq(

    id INT AUTO_INCREMENT PRIMARY KEY
);

CREATE TABLE Archer (

    ArcherID VARCHAR(10) PRIMARY KEY,
    FirstName VARCHAR(75) NOT NULL, 
    LastName VARCHAR(100) NOT NULL,
    DOB DATE NOT NULL, 
    Gender VARCHAR(10) NOT NULL
);

DELIMITER $$
CREATE PROCEDURE archer_insert (
    IN p_FirstName VARCHAR(75),
    IN p_LastName VARCHAR(100),
    IN p_DOB DATE,
    IN p_Gender VARCHAR(10)
)
BEGIN
    INSERT INTO archer_id_seq VALUES (NULL);
    SET @new_id = CONCAT('A', LAST_INSERT_ID());
    INSERT INTO Archer (ArcherID, FirstName, LastName, DOB, Gender)
    VALUES (@new_id, p_FirstName, p_LastName, p_DOB, p_Gender);
END$$ 
DELIMITER; 



CREATE TABLE equipment_id_seq(

    id INT AUTO_INCREMENT PRIMARY KEY
);

CREATE TABLE Equipment (

    EquipmentID VARCHAR(10) PRIMARY KEY,
    Types VARCHAR(25) NOT NULL
);

DELIMITER $$
CREATE PROCEDURE equipment_insert (
    IN p_Types VARCHAR(25)
)
BEGIN
    INSERT INTO equipment_id_seq VALUES (NULL);
    SET @new_id = CONCAT('E', LAST_INSERT_ID());
    INSERT INTO Equipment (EquipmentID, Types)
    VALUES (@new_id, p_Types);
END$$ 
DELIMITER ;



CREATE TABLE division_id_seq(

    id INT AUTO_INCREMENT PRIMARY KEY
);

CREATE TABLE Division (

    DivisionID VARCHAR(10) PRIMARY KEY,
    DivName VARCHAR(25) NOT NULL,
    Information VARCHAR(255)
);

DELIMITER $$
CREATE PROCEDURE division_insert (
    IN p_DivName VARCHAR(25),
    IN p_Information VARCHAR(255)
)
BEGIN
    INSERT INTO division_id_seq VALUES (NULL);
    SET @new_id = CONCAT('D', LAST_INSERT_ID());
    INSERT INTO Division (DivisionID, DivName, Information)
    VALUES (@new_id, p_DivName, p_Information);
END$$
DELIMITER ;



CREATE TABLE range_id_seq(

    id INT AUTO_INCREMENT PRIMARY KEY
);

CREATE TABLE Ranges (

    RangeID VARCHAR(10) PRIMARY KEY,
    Distance INT NOT NULL
);

DELIMITER $$
CREATE PROCEDURE range_insert (
    IN p_Distance INT
)
BEGIN
    INSERT INTO range_id_seq VALUES (NULL);
    SET @new_id = CONCAT('R', LAST_INSERT_ID());
    INSERT INTO Ranges (RangeID, Distance)
    VALUES (@new_id, p_Distance);
END$$ 
DELIMITER ;



CREATE TABLE session_id_seq(

    id INT AUTO_INCREMENT PRIMARY KEY
);

CREATE TABLE Session (

    SessionID VARCHAR(10) PRIMARY KEY,
    NoOfArrows INT NOT NULL,
    NoOfEnds INT NOT NULL,
    EndState BOOLEAN
);

DELIMITER $$
CREATE PROCEDURE session_insert (
    IN p_NoOfArrows INT,
    IN p_NoOfEnds INT,
    IN p_EndState BOOLEAN
)
BEGIN
    INSERT INTO session_id_seq VALUES (NULL);
    SET @new_id = CONCAT('S', LAST_INSERT_ID());
    INSERT INTO Session (SessionID, NoOfArrows, NoOfEnds, EndState)
    VALUES (@new_id, p_NoOfArrows, p_NoOfEnds, p_EndState);
END$$ 
DELIMITER ;



CREATE TABLE competition_id_seq(

    id INT AUTO_INCREMENT PRIMARY KEY
);

CREATE TABLE Competition (

    CompetitionID VARCHAR(10) PRIMARY KEY,
    CompName VARCHAR(25) NOT NULL,
    StartDate DATE, 
    EndDate DATE
);

DELIMITER $$
CREATE PROCEDURE competition_insert (
    IN p_CompName VARCHAR(25),
    IN p_StartDate DATE,
    IN p_EndDate DATE
)
BEGIN
    INSERT INTO competition_id_seq VALUES (NULL);
    SET @new_id = CONCAT('C', LAST_INSERT_ID());
    INSERT INTO Competition (CompetitionID, CompName, StartDate, EndDate)
    VALUES (@new_id, p_CompName, p_StartDate, p_EndDate);
END$$ 
DELIMITER ;



CREATE TABLE ArcherEquipment (

    ArcherID VARCHAR(10),
    EquipmentID VARCHAR(10),
    PRIMARY KEY (ArcherID, EquipmentID),
    FOREIGN KEY (ArcherID) REFERENCES Archer(ArcherID),
    FOREIGN KEY (EquipmentID) REFERENCES Equipment(EquipmentID)
);

CREATE TABLE ArcherDivision (

    ArcherID VARCHAR(10), 
    EquipmentID VARCHAR(10), 
    PRIMARY KEY (ArcherID, EquipmentID), 
    FOREIGN KEY (ArcherID) REFERENCES Archer(ArcherID), 
    FOREIGN KEY (EquipmentID) REFERENCES Equipment(EquipmentID)
);

-- Incomplete no RoundID exists for now
CREATE TABLE Staging (
    ArcherID VARCHAR(10), 
    EquipmentID VARCHAR(10), 
    RoundID VARCHAR(10), 
    StageDate DATE, 
    StageTime TIME
);

-- The below are also incomplete with EndID, ScoreID and RoundID.
CREATE TABLE End (


);

CREATE TABLE Score (


);

CREATE TABLE Round (


);