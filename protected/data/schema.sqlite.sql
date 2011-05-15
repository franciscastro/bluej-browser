DROP TABLE IF EXISTS Term;
DROP TABLE IF EXISTS UserTerm;
DROP TABLE IF EXISTS SessionTerm;
DROP TABLE IF EXISTS ImportSessionTerm;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Session;
DROP TABLE IF EXISTS Section;
DROP TABLE IF EXISTS UserSection;
DROP TABLE IF EXISTS CompileSession;
DROP TABLE IF EXISTS CompileSessionEntry;
DROP TABLE IF EXISTS InvocationSession;
DROP TABLE IF EXISTS InvocationSessionEntry;
DROP TABLE IF EXISTS ImportSession;
DROP TABLE IF EXISTS Import;
DROP TABLE IF EXISTS EqCalculation;
DROP TABLE IF EXISTS Confusion;
DROP TABLE IF EXISTS ErrorClass;

CREATE TABLE Term
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	parentId INTEGER NOT NULL,
	name TEXT,
	CONSTRAINT FK_term_term FOREIGN KEY (parentId)
		REFERENCES Term(id)
);

CREATE TABLE Section
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	name TEXT NOT NULL,
	yearId INTEGER NOT NULL,
	courseId INTEGER NOT NULL,
	sectionId INTEGER NOT NULL,
	CONSTRAINT FK_section_term1 FOREIGN KEY (yearId)
		REFERENCES Term(id),
	CONSTRAINT FK_section_term2 FOREIGN KEY (courseId)
		REFERENCES Term(id),
	CONSTRAINT FK_section_term3 FOREIGN KEY (sectionId)
		REFERENCES Term(id)
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

CREATE TABLE UserTerm
(
	userId INTEGER NOT NULL,
	termId INTEGER NOT NULL,
	CONSTRAINT FK_ut_term FOREIGN KEY (termId)
		REFERENCES Term (id),
	CONSTRAINT FK_ut_user FOREIGN KEY (userId)
		REFERENCES User (id)
);

CREATE TABLE SessionTerm
(
	sessionId INTEGER NOT NULL,
	termId INTEGER NOT NULL,
	CONSTRAINT FK_st_term FOREIGN KEY (termId)
		REFERENCES Term (id),
	CONSTRAINT FK_st_session FOREIGN KEY (sessionId)
		REFERENCES Session (id)
);

CREATE TABLE ImportSessionTerm
(
	importSessionId INTEGER NOT NULL,
	termId INTEGER NOT NULL,
	CONSTRAINT FK_st_term FOREIGN KEY (termId)
		REFERENCES Term (id),
	CONSTRAINT FK_st_importSession FOREIGN KEY (importSessionId)
		REFERENCES ImportSession (id)
);

CREATE TABLE User
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	username VARCHAR(128) NOT NULL,
	password CHAR(40) NOT NULL,
	name TEXT,
	roleId INTEGER NOT NULL
);

CREATE TABLE Session
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	userId INTEGER,
	date INTEGER,
	type TEXT,
	CONSTRAINT FK_user_session FOREIGN KEY (userId)
		REFERENCES User (id)
);

CREATE TABLE CompileSession
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
	sessionId TEXT,
	projectPath TEXT,
	packagePath TEXT,
	deltaName TEXT,
	CONSTRAINT FK_compileSession_session FOREIGN KEY (id)
		REFERENCES Session (id)
);

CREATE TABLE CompileSessionEntry
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	compileSessionId INTEGER,
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
	compilesPerFile INTEGER,
	totalCompiles INTEGER,
	CONSTRAINT FK_compileSessionEntry_compileSession FOREIGN KEY (compileSessionId)
		REFERENCES CompileSession (id)
);

CREATE TABLE InvocationSession
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
	sessionId TEXT,
	projectPath TEXT,
	packagePath TEXT,
	deltaName TEXT,
	CONSTRAINT FK_invocationSession_session FOREIGN KEY (id)
		REFERENCES Session (id)
);

CREATE TABLE InvocationSessionEntry
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	invocationSessionId INTEGER,
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
	CONSTRAINT FK_invocationSessionEntry_invocationSession FOREIGN KEY (invocationSessionId)
		REFERENCES InvocationSession (id)
);

CREATE TABLE ImportSession
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	sectionId INTEGER,
	source TEXT,
	path TEXT,
	start INTEGER,
	end INTEGER,
	remarks TEXT
);

CREATE TABLE Import
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	importSessionId INTEGER,
	sessionId INTEGER,
	path TEXT,
	CONSTRAINT FK_import_session FOREIGN KEY (sessionId)
		REFERENCES Session (id),
	CONSTRAINT FK_import_importSession FOREIGN KEY (importSessionId)
		REFERENCES ImportSession (id)
);

CREATE TABLE EqCalculation
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	compileSessionId INTEGER,
	eq REAL,
	CONSTRAINT FK_eqCalculation_compileSession FOREIGN KEY (compileSessionId)
		REFERENCES CompileSession (id)
);

CREATE TABLE Confusion
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	compileSessionId INTEGER,
	confusion REAL,
	CONSTRAINT FK_confusion_compileSession FOREIGN KEY (compileSessionId)
		REFERENCES CompileSession (id)
);

CREATE TABLE ErrorClass
(
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  compileSessionEntryId INTEGER,
  error TEXT,
  CONSTRAINT FK_errorClass_compileSessionEntry FOREIGN KEY (compileSessionEntryId)
    REFERENCES CompileSessionEntry (id)
);

INSERT INTO Term VALUES (1, 0, "Root");
INSERT INTO Term VALUES (2, 1, "Year");
INSERT INTO Term VALUES (3, 1, "Course");
INSERT INTO Term VALUES (4, 1, "Section");
INSERT INTO Term VALUES (5, 1, "Lab");
INSERT INTO Term VALUES (6, 1, "Other");

INSERT INTO Term VALUES (NULL, 2, "2010-2011");
INSERT INTO Term VALUES (NULL, 3, "CS21a");
INSERT INTO Term VALUES (NULL, 4, "A");

INSERT INTO User VALUES (NULL, 'admin', 'f97baaf2592507e4bc91f3a7c0a25c2f3d6a28ac', 'Administrator', 1);
