DROP TABLE IF EXISTS Tag;
DROP TABLE IF EXISTS LogSessionTag;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Section;
DROP TABLE IF EXISTS UserSection;
DROP TABLE IF EXISTS CompileLog;
DROP TABLE IF EXISTS CompileLogEntry;
DROP TABLE IF EXISTS InvocationLog;
DROP TABLE IF EXISTS InvocationLogEntry;
DROP TABLE IF EXISTS LogSession;
DROP TABLE IF EXISTS Log;
DROP TABLE IF EXISTS EqCalculation;
DROP TABLE IF EXISTS Confusion;
DROP TABLE IF EXISTS ErrorClass;

CREATE TABLE Tag
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	parentId INTEGER NOT NULL,
	name TEXT,
	CONSTRAINT FK_tag_tag FOREIGN KEY (parentId)
		REFERENCES Tag(id)
);

CREATE TABLE Section
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name TEXT NOT NULL,
	yearId INTEGER NOT NULL,
	courseId INTEGER NOT NULL,
	sectionId INTEGER NOT NULL,
	active BIT NOT NULL,
	CONSTRAINT FK_section_tag1 FOREIGN KEY (yearId)
		REFERENCES Tag(id),
	CONSTRAINT FK_section_tag2 FOREIGN KEY (courseId)
		REFERENCES Tag(id),
	CONSTRAINT FK_section_tag3 FOREIGN KEY (sectionId)
		REFERENCES Tag(id)
);

CREATE TABLE UserSection
(
	userId INTEGER NOT NULL,
	sectionId INTEGER NOT NULL,
	CONSTRAINT FK_us_section FOREIGN KEY (sectionId)
		REFERENCES Class (id),
	CONSTRAINT FK_us_user FOREIGN KEY (userId)
		REFERENCES User (id)
);

CREATE TABLE LogSessionTag
(
	logSessionId INTEGER NOT NULL,
	tagId INTEGER NOT NULL,
	CONSTRAINT FK_st_tag FOREIGN KEY (tagId)
		REFERENCES Tag (id),
	CONSTRAINT FK_st_logSession FOREIGN KEY (logSessionId)
		REFERENCES LogSession (id)
);

CREATE TABLE User
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(128) NOT NULL,
	password CHAR(40) NOT NULL,
	name TEXT,
	computer VARCHAR(128),
	roleId INTEGER NOT NULL
);

CREATE TABLE CompileLog
(
	id INTEGER NOT NULL PRIMARY KEY,
	deltaVersion TEXT,
	extensionVersion TEXT,
	systemUser TEXT,
	home TEXT,
	osName TEXT,
	osVersion TEXT,
	osArch TEXT,
	ipAddress TEXT,
	hostName TEXT,
	locationId TEXT,
	projectId TEXT,
	logId TEXT,
	projectPath TEXT,
	packagePath TEXT,
	deltaName TEXT,
	CONSTRAINT FK_compileLog_log FOREIGN KEY (id)
		REFERENCES Session (id)
);

CREATE TABLE CompileLogEntry
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	logId INTEGER,
	timestamp INTEGER,
	deltaSequenceNumber INTEGER,
	deltaStartTime INTEGER,
	deltaEndTime INTEGER,
	filePath TEXT,
	fileName TEXT,
	fileContents TEXT,
	fileEncoding TEXT,
	compileSuccessful INTEGER,
	messageType TEXT,
	messageText TEXT,
	messageLineNumber INTEGER,
	messageColumnNumber INTEGER,
	compilesPerFile INTEGER,
	totalCompiles INTEGER,
	CONSTRAINT FK_compileLogEntry_compileLog FOREIGN KEY (logId)
		REFERENCES CompileLog (id)
);

CREATE TABLE InvocationLog
(
	id INTEGER NOT NULL PRIMARY KEY,
	deltaVersion TEXT,
	extensionVersion TEXT,
	systemUser TEXT,
	home TEXT,
	osName TEXT,
	osVersion TEXT,
	osArch TEXT,
	ipAddress TEXT,
	hostName TEXT,
	locationId TEXT,
	projectId TEXT,
	logId TEXT,
	projectPath TEXT,
	packagePath TEXT,
	deltaName TEXT,
	CONSTRAINT FK_invocationLog_log FOREIGN KEY (id)
		REFERENCES Session (id)
);

CREATE TABLE InvocationLogEntry
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	logId INTEGER,
	timestamp INTEGER,
	deltaSequenceNumber INTEGER,
	deltaStartTime INTEGER,
	deltaEndTime INTEGER,
	package TEXT,
	className TEXT,
	objectName TEXT,
	methodName TEXT,
	parameterTypes TEXT,
	parameters TEXT,
	result TEXT,
	invocationStatus TEXT,
	CONSTRAINT FK_invocationLogEntry_invocationLog FOREIGN KEY (logId)
		REFERENCES InvocationLog (id)
);

CREATE TABLE LogSession
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	sectionId INTEGER,
	source TEXT,
	path TEXT,
	start INTEGER,
	end INTEGER,
	remarks TEXT
);

CREATE TABLE Log
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	logSessionId INTEGER,
	userId INTEGER,
	date INTEGER,
 	CONSTRAINT FK_user_log FOREIGN KEY (userId)
		REFERENCES User (id),
	CONSTRAINT FK_log_logSession FOREIGN KEY (logSessionId)
		REFERENCES LogSession (id)
);

CREATE TABLE EqCalculation
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	logId INTEGER,
	eq REAL,
	CONSTRAINT FK_eqCalculation_compileLog FOREIGN KEY (logId)
		REFERENCES CompileLog (id)
);

CREATE TABLE Confusion
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	logId INTEGER,
	confusion REAL,
	clips INTEGER,
	CONSTRAINT FK_confusion_compileLog FOREIGN KEY (logId)
		REFERENCES CompileLog (id)
);

CREATE TABLE ErrorClass
(
  id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  compileLogEntryId INTEGER,
  error TEXT,
  CONSTRAINT FK_errorClass_compileLogEntry FOREIGN KEY (compileLogEntryId)
    REFERENCES CompileLogEntry (id)
);

INSERT INTO Tag VALUES (1, 0, "Root");
INSERT INTO Tag VALUES (2, 1, "Year");
INSERT INTO Tag VALUES (3, 1, "Course");
INSERT INTO Tag VALUES (4, 1, "Section");
INSERT INTO Tag VALUES (5, 1, "Lab");
INSERT INTO Tag VALUES (6, 1, "Other");

INSERT INTO User VALUES (NULL, 'admin', 'f97baaf2592507e4bc91f3a7c0a25c2f3d6a28ac', 'Administrator', '', 1);
