DROP DATABASE tcabs;
CREATE DATABASE tcabs;

-- DROP TABLE role;
CREATE TABLE role (
RID			INT 		AUTO_INCREMENT,
RoleName		Varchar(255)	NOT NULL,
SecurityLVL		INT 		NOT NULL,
Description		Varchar(255),
PRIMARY KEY	(RID)
);

-- DROP TABLE users;
CREATE TABLE users (
UID			INT		AUTO_INCREMENT,
Fname			Varchar(255)	NOT NULL,
Lname			Varchar(255)	NOT NULL,
Gender		Varchar(10),
Work_Email		Varchar(255),
Personal_Email	Varchar(255),
Phone			INT,
Start_Date		DATE,
End_Date		DATE,
Password		Varchar(10),
PRIMARY KEY (UID)
);

-- DROP TABLE subjects;
CREATE TABLE subjects(
SID		INT 		AUTO_INCREMENT,
Name		Varchar(255)	NOT NULL,
Faculty		Varchar(255),
PRIMARY KEY (SID)
);

-- DROP TABLE unit_offering;
CREATE TABLE unit_offering(
OID			INT 		AUTO_INCREMENT,
Name			Varchar(255)	NOT NULL,
Start_Period		DATE,
Census_Period	DATE,
End_Period		DATE,
Pre_requistes	Varchar(255),
SID		INT,
-- FOREIGN KEY (DAY/MONTH/YEAR)	REFERENCES Role(DAY/MONTH/YEAR)
FOREIGN KEY (SID) 				REFERENCES Subjects(SID),
PRIMARY KEY (OID)
);

-- DROP TABLE enroled;
CREATE TABLE enroled (
EID		INT		NOT NULL,
RID		INT,
UID		INT,
OID		INT,
PRIMARY KEY (EID),
    CONSTRAINT FK_RID FOREIGN KEY (RID)	REFERENCES Role(RID),
    CONSTRAINT FK_UID FOREIGN KEY (UID)	REFERENCES Users(UID),
    CONSTRAINT FK_OID FOREIGN KEY (OID)	REFERENCES UNIT_OFFERING(OID)
-- FOREIGN KEY (RID)		REFERENCES Role(RID),
-- FOREIGN KEY (UID)		REFERENCES Users(UID),
-- FOREIGN KEY (DAY/MONTH/YEAR)	REFERENCES Role(DAY/MONTH/YEAR),
-- FOREIGN KEY (OID)		REFERENCES UNIT_OFFERING(OID)
);

-- DROP TABLE teams;
CREATE TABLE teams(
TID		INT 		AUTO_INCREMENT,
Name		Varchar(255)	NOT NULL,
NumOfStu	INT,
PRIMARY KEY (TID)
);

-- DROP TABLE userteam;
CREATE TABLE userteam(
Manager_Role	Varchar(255)	NOT NULL,
UID		INT,
TID		INT,
FOREIGN KEY (UID)		REFERENCES users(UID),
FOREIGN KEY (TID)		REFERENCES teams(TID),
PRIMARY KEY (UID,TID)
);

-- DROP TABLE peerassessment;
CREATE TABLE peerassessment(
PID		INT 		PRIMARY KEY,
FORM		Varchar(255)	NOT NULL,
UID		INT,
TID		INT,
    CONSTRAINT FK_UTID FOREIGN KEY (UID,TID)	REFERENCES userteam(UID,TID)
-- FOREIGN KEY (UID,TID)		REFERENCES userteam(UID,TID)
);

-- DROP TABLE meeting;
CREATE TABLE meeting(
MID		INT 		AUTO_INCREMENT,
Agender	Varchar(255)	NOT NULL,
Time		INT,
Minutes	INT,
Duration	INT,
Sup_Notes	Varchar(255),
-- FOREIGN KEY (DAY/MONTH/YEAR)	REFERENCES Role(DAY/MONTH/YEAR),
PRIMARY KEY (MID)
);

-- DROP TABLE paysheets;
CREATE TABLE paysheets(
SHEETID		INT 		AUTO_INCREMENT,
Week			INT,
Time			INT,
Project_Budget	Varchar(255),
Spent			Varchar(255),
Remain		Varchar(255),
-- FOREIGN KEY (DAY/MONTH/YEAR)	REFERENCES Role(DAY/MONTH/YEAR),
PRIMARY KEY (SHEETID)
);

-- DROP TABLE projectRole;
CREATE TABLE projectRole(
RoleName	INT	AUTO_INCREMENT,
Salary		Varchar(255),
PRIMARY KEY (RoleName),
SHEETID		INT,
FOREIGN KEY (SHEETID)	REFERENCES paysheets(SHEETID)
);

-- DROP TABLE projects;
CREATE TABLE projects(
PROID		INT	AUTO_INCREMENT,
Pro_File	Varchar(255),
Min_Size	INT,
Max_Size	INT,
PRIMARY KEY (PROID)
);

-- DROP TABLE TeamProjects;
CREATE TABLE TeamProjects(
PROID		INT,
TID			INT,
FOREIGN KEY (PROID)		REFERENCES Projects(PROID),
FOREIGN KEY (TID)		REFERENCES Teams(TID)
);

