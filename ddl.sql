DROP DATABASE tcabs;
CREATE DATABASE tcabs;

CREATE TABLE UserRole (
	userRoleID					INT				AUTO_INCREMENT,
	userType		VARCHAR(50)				NOT NULL,

	PRIMARY KEY (userRoleID)
);

CREATE TABLE Functions (
	functionID			INT						AUTO_INCREMENT,
	procName		VARCHAR(50)				NOT NULL,

	PRIMARY KEY (functionID)
);

CREATE TABLE Permission (
	permissionID				INT				AUTO_INCREMENT,
	userRoleID					INT				NOT NULL,
	functionID					INT				NOT NULL,

	PRIMARY KEY (permissionID),
	FOREIGN KEY (userRoleID) REFERENCES UserRole (userRoleID),
	FOREIGN KEY (functionID) REFERENCES Functions (functionID)
);

CREATE TABLE Users (
	userID							INT				AUTO_INCREMENT,
	fName				VARCHAR(255)			NOT NULL,
	lName				VARCHAR(255)			NOT NULL,
	userRoleID					INT				NOT NULL,
	gender			VARCHAR(1),
	pNum				VARCHAR(255),
	email				VARCHAR(255)			NOT NULL,
	pwd					VARCHAR(20)				NOT NULL,

	PRIMARY KEY (userID),
	FOREIGN KEY (userRoleID) REFERENCES UserRole (userRoleID)
);

CREATE TABLE Unit (
	unitID							INT				AUTO_INCREMENT,
	unitCode		VARCHAR(10)				NOT NULL,
	unitName		VARCHAR(100)			NOT NULL,
	faculty			VARCHAR(10)				NOT NULL,

	PRIMARY KEY (unitID)
);

CREATE TABLE TeachingPeriod (
	term			VARCHAR(10)				NOT NULL,
	year			VARCHAR(10)				NOT NULL,

	PRIMARY KEY (term, year)
);

CREATE TABLE UnitOffering (
	unitOfferingID			INT				AUTO_INCREMENT,
	unitID							INT				NOT NULL,
	convenorID					INT				NOT NULL,
	term				VARCHAR(10)				NOT NULL,
	year				VARCHAR(10)				NOT NULL,
	censusDate	VARCHAR(20)				NOT NULL,

	PRIMARY KEY (unitOfferingID),
	FOREIGN KEY (unitID) REFERENCES Unit(unitID),
	FOREIGN KEY (convenorID) REFERENCES Users(userID),
	FOREIGN KEY (term, year) REFERENCES TeachingPeriod(term, year)
);

CREATE TABLE Enrolment (
	enrolmentID					INT				AUTO_INCREMENT,
	studentID						INT				NOT NULL,
	unitOfferingID			INT				NOT NULL,

	PRIMARY KEY (enrolmentID),
	FOREIGN KEY (studentID) REFERENCES Users(userID),
	FOREIGN KEY (unitOfferingID) REFERENCES UnitOffering(unitOfferingID)
);

INSERT INTO tcabs.UserRole VALUES (1, "admin");
INSERT INTO tcabs.UserRole VALUES (2, "admin-convenor");
INSERT INTO tcabs.UserRole VALUES (3, "admin-convenor-supervisor");
INSERT INTO tcabs.UserRole VALUES (4, "convenor");
INSERT INTO tcabs.UserRole VALUES (5, "convenor-supervisor");
INSERT INTO tcabs.UserRole VALUES (6, "supervisor");
INSERT INTO tcabs.UserRole VALUES (7, "admin-supervisor");
INSERT INTO tcabs.UserRole VALUES (8, "student");

INSERT INTO tcabs.Users VALUES (1, "Daenerys", "Targaryen", "1", "F", "0412323443", "dtargaryen@gmail.com", "motherofdragons");
INSERT INTO tcabs.Users VALUES (2, "Tyrion", "Lannister", "6", "M", "0412332543", "tlannister@gmail.com", "lannisteralwayspaysitsdebt");
INSERT INTO tcabs.Users VALUES (3, "John", "Snow", "8", "M", "0412332243", "jsnow@gmail.com", "kinginthenorth");
INSERT INTO tcabs.Users VALUES (4, "Robert", "Baratheon", "4", "M", "0412332263", "rbaratheon@gmail.com", "rulerofsevenkingdoms");
INSERT INTO tcabs.Users VALUES (5, "Arya", "Stark", "3", "F", "0412332263", "astark@gmail.com", "thereisonlyonegod");

INSERT INTO tcabs.Unit VALUES (1, "ICT30001", "Information Technology Project", "FSET");
INSERT INTO tcabs.Unit VALUES (2, "INF30011", "Database Implementation", "FSET");
INSERT INTO tcabs.Unit VALUES (3, "STA10003", "Foundation of Statistics", "FSET");
INSERT INTO tcabs.Unit VALUES (4, "STA20010", "Statisical Computing", "FSET");

INSERT INTO tcabs.TeachingPeriod VALUES ("Semester 1", "2019");
INSERT INTO tcabs.TeachingPeriod VALUES ("Semester 2", "2019");
INSERT INTO tcabs.TeachingPeriod VALUES ("Semester 2", "2018");
INSERT INTO tcabs.TeachingPeriod VALUES ("Winter", "2018");
INSERT INTO tcabs.TeachingPeriod VALUES ("Summer", "2018");

INSERT INTO tcabs.UnitOffering VALUES (1, 1, 4, "Semester 2", "2018", "31 March 2018");

INSERT INTO tcabs.Enrolment VALUES (1, 3, 1);

INSERT INTO tcabs.Functions VALUES (1, "registerUser");

INSERT INTO tcabs.Permission VALUES (1, 1, 1);

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `register_user`(IN `_fName` VARCHAR(255), IN `_lName` VARCHAR(255), IN `_gender` VARCHAR(1), IN `_pNum` VARCHAR(255), IN `_email` VARCHAR(255), IN `_pwd` VARCHAR(255), IN `_is_admin` INT(1), IN `_is_convenor` INT(1), IN `_is_supervisor` INT(1), IN `_is_student` INT(1), OUT `_userID` INT(1))
    NO SQL
    COMMENT 'Register into User Table'
begin
if _fName = "" or _lName = ""  or _gender = ""  or _pNum = ""  or _email = ""  or _pwd = "" THEN
signal sqlstate '99999' set message_text = 'Please enter all fields!';

else

insert into tcabs.users(users.userID, users.fName, users.lName, users.gender, users.pNum, users.email, users.pwd) VALUES (NULL, _fName, _lName, _gender, _pNum, _email, _pwd);

set _userID = LAST_INSERT_ID();

IF _is_admin = 1 THEN INSERT INTO tcabs.useruserrole(useruserrole.uurID, useruserrole.userID, useruserrole.userRoleID) values (NULL, _userID, 1);
END IF;
IF _is_convenor = 1 THEN INSERT INTO tcabs.useruserrole(useruserrole.uurID, useruserrole.userID, useruserrole.userRoleID) values (NULL, _userID, 2);
END IF;
IF _is_supervisor = 1 THEN INSERT INTO tcabs.useruserrole(useruserrole.uurID, useruserrole.userID, useruserrole.userRoleID) values (NULL, _userID, 3);
END IF;
IF _is_student = 1 THEN INSERT INTO tcabs.useruserrole(useruserrole.uurID, useruserrole.userID, useruserrole.userRoleID) values (NULL, _userID, 4);
END IF;
end if;

end$$
DELIMITER ;
