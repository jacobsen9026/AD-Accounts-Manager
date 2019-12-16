--
-- File generated with SQLiteStudio v3.2.1 on Sun Dec 15 22:26:04 2019
--
-- Text encoding used: System
--
PRAGMA foreign_keys = off;
BEGIN TRANSACTION;

-- Table: App
CREATE TABLE App (ID PRIMARY KEY DEFAULT (1) CHECK (ID = 1), Name STRING NOT NULL DEFAULT "School Accounts Manager", Force_HTTPS BOOLEAN NOT NULL DEFAULT (FALSE), MOTD TIME, Debug_Mode BOOLEAN DEFAULT (FALSE) NOT NULL, Admin_Password STRING DEFAULT ('5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8'), Protected_Admin_Usernames STRING, Websitie_FQDN STRING, App_Version STRING DEFAULT ('0.1.0'), Database_Version STRING DEFAULT ('0.1.0'), User_Helpdesk_URL STRING, Update_Check_URL STRING DEFAULT ('https://raw.githubusercontent.com/jacobsen9026/School-Accounts-Manager/master/version.txt'));

-- Table: Auth
CREATE TABLE Auth (ID INTEGER PRIMARY KEY, App_ID INTEGER REFERENCES App (ID) ON DELETE CASCADE ON UPDATE CASCADE UNIQUE, Tech_AD_Group STRING, Admin_AD_Group STRING, Power_AD_Group STRING, Basic_AD_Group STRING, Tech_GA_Group STRING, Admin_GA_Group STRING, Power_GA_Group STRING, Basic_GA_Group STRING, LDAP_Enabled BOOLEAN, LDAP_Server STRING, LDAP_Port INTEGER, LDAP_Username STRING, LDAP_Password STRING, LDAP_Use_SSL BOOLEAN, OAuth_Enabled BOOLEAN, Session_Timeout INTEGER NOT NULL DEFAULT (1200));

-- Table: Districts
CREATE TABLE Districts (ID INTEGER PRIMARY KEY ASC AUTOINCREMENT, Name TEXT UNIQUE NOT NULL, Grade_Span_From STRING, Grade_Span_To STRING, Abbreviation STRING, AD_FQDN STRING, GA_FQDN STRING, Parent_Email_Group STRING, Staff_AD_OU STRING, Staff_GA_OU STRING, Staff_GA_Group STRING, Staff_AD_Group STRING, AD_NetBIOS STRING, Staff_AD_Home_Path STRING, Staff_AD_Logon_Script STRING);

-- Table: Email
CREATE TABLE Email (ID INTEGER PRIMARY KEY AUTOINCREMENT, App_ID INTEGER REFERENCES App (ID) ON DELETE CASCADE ON UPDATE CASCADE UNIQUE, From_Address STRING, From_Name STRING, Admin_Email_Addresses STRING, Welcome_Email_BCC STRING, Welcome_Email STRING, Reply_To_Address STRING, Reply_To_Name STRING, Use_SMTP_SSL BOOLEAN, SMTP_Server STRING, SMTP_Port INTEGER, Use_SMTP_Auth BOOLEAN, SMTP_Username STRING, SMTP_Password STRING);

-- Table: Grades
CREATE TABLE Grades (ID INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, School_ID INTEGER REFERENCES Schools (ID) ON DELETE CASCADE ON UPDATE CASCADE NOT NULL, Name STRING, Abbreviation STRING, Value STRING, Parent_Email_Group STRING, Student_GA_OU STRING, Student_AD_OU STRING, Student_GA_Group STRING, Student_AD_Group STRING, Student_AD_Home_Path STRING, Student_AD_Logon_Script STRING, Student_AD_Description STRING, Force_Student_Password_Change BOOLEAN DEFAULT (FALSE) NOT NULL, Staff_GA_OU STRING, Staff_AD_OU STRING, Staff_GA_Group STRING, Staff_AD_Group STRING, Staff_AD_Home_Path STRING, Staff_AD_Logon_Script STRING, Staff_AD_Description STRING);

-- Table: Logon
CREATE TABLE Logon (ID INTEGER PRIMARY KEY AUTOINCREMENT, Timestamp DATETIME DEFAULT (SYSDATETIME()), Username STRING);

-- Table: Schools
CREATE TABLE Schools (ID INTEGER PRIMARY KEY ASC AUTOINCREMENT, District_ID INTEGER REFERENCES Districts (ID) ON DELETE CASCADE ON UPDATE CASCADE, Name TEXT, Staff_GA_OU STRING, Staff_AD_OU STRING, Staff_GA_Group STRING, Staff_AD_Group STRING, Other_Staff_Email_Groups STRING, Staff_AD_Home_Path STRING, Staff_AD_Logon_Script STRING, Staff_AD_Description STRING);

-- Table: Sessions
CREATE TABLE Sessions (ID INTEGER PRIMARY KEY AUTOINCREMENT, Last_Authenticated DATETIME DEFAULT (SYSDATETIME()), User_Object STRING, Token STRING UNIQUE);

-- Table: Teams
CREATE TABLE Teams (ID INTEGER PRIMARY KEY AUTOINCREMENT, Grade_ID INTEGER REFERENCES Grades (ID) ON DELETE CASCADE ON UPDATE CASCADE, Name STRING, Student_GA_OU STRING, Student_AD_OU STRING, Student_GA_Group STRING, Student_AD_Group STRING, Student_AD_Home_Path STRING, Student_AD_Description STRING, Student_Logon_Script STRING, Parent_Email_Group STRING, Staff_GA_OU STRING, Staff_AD_OU STRING, Staff_GA_Group STRING, Staff_AD_Group STRING, Staff_AD_Home_Path STRING, Staff_AD_Description STRING, Staff_Logon_Script STRING);

-- Trigger: Initialize New Tables
CREATE TRIGGER "Initialize New Tables" BEFORE UPDATE ON App BEGIN INSERT INTO Auth (App_ID) SELECT (new.ID) WHERE NOT EXISTS(SELECT * FROM Auth WHERE App_ID = new.ID);
INSERT INTO Email (App_ID) SELECT (new.ID) WHERE NOT EXISTS(SELECT * FROM Email WHERE App_ID = new.ID); END;

-- Trigger: Setup App
CREATE TRIGGER "Setup App" AFTER INSERT ON App BEGIN INSERT INTO Auth (App_ID) VALUES (New.ID); INSERT INTO Email (App_ID) VALUES (New.ID); END;

COMMIT TRANSACTION;
PRAGMA foreign_keys = on;
