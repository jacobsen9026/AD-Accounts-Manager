PRAGMA foreign_keys = off;
BEGIN TRANSACTION;
DROP TABLE IF EXISTS "EmailTemplate";
CREATE TABLE IF NOT EXISTS "EmailTemplate"
(
    "ID"      INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "Name"    STRING,
    "Subject" STRING,
    "Body"    STRING
);
DROP TABLE IF EXISTS "App";
CREATE TABLE IF NOT EXISTS "App"
(
    "ID"                       TEXT             DEFAULT 1,
    "Name"                     STRING  NOT NULL DEFAULT 'Active Directory Accounts Manager',
    "Force_HTTPS"              BOOLEAN NOT NULL DEFAULT 0,
    "MOTD"                     TEXT,
    "Debug_Mode"               BOOLEAN NOT NULL DEFAULT 0,
    "Websitie_FQDN"            STRING,
    "User_Helpdesk_URL"        STRING,
    "Update_Check_URL"         STRING           DEFAULT 'https://raw.githubusercontent.com/jacobsen9026/AD-Accounts-Manager/master/update/update.json',
    "Setup_Completed"          BOOLEAN          DEFAULT 0,
    "Last_Update_Check"        int              DEFAULT 0,
    "Latest_Available_Version" STRING,
    "App_Icon"                 STRING,
    PRIMARY KEY ("ID"),
    CHECK (ID = 1)
);
DROP TABLE IF EXISTS "Domain";
CREATE TABLE IF NOT EXISTS "Domain"
(
    "ID"                      INTEGER DEFAULT 1 PRIMARY KEY AUTOINCREMENT,
    "Name"                    TEXT UNIQUE,
    "Abbreviation"            STRING,
    "AD_FQDN"                 STRING,
    "AD_Server"               STRING,
    "AD_BaseDN"               STRING,
    "AD_NetBIOS"              STRING,
    "AD_Username"             STRING,
    "AD_Password"             STRING,
    "GA_FQDN"                 STRING,
    "Default_Username_Format" STRING,
    "AD_Use_TLS"              BOOLEAN DEFAULT 0,
    CHECK (ID = 1)
);
DROP TABLE IF EXISTS "User";
CREATE TABLE IF NOT EXISTS "User"
(
    "ID"        INTEGER PRIMARY KEY AUTOINCREMENT,
    "Username"  STRING UNIQUE,
    "Token"     STRING UNIQUE,
    "Theme"     STRING,
    "Privilege" INTEGER DEFAULT 0
);
DROP TABLE IF EXISTS "Schema";
CREATE TABLE IF NOT EXISTS "Schema"
(
    "ID"             INTEGER DEFAULT 1,
    "Schema_Version" TEXT    DEFAULT '0.1.4'
);
DROP TABLE IF EXISTS "PrivilegeLevel";
CREATE TABLE IF NOT EXISTS "PrivilegeLevel"
(
    "ID"            INTEGER PRIMARY KEY AUTOINCREMENT,
    "Domain_ID"     INTEGER,
    "AD_Group_Name" STRING NOT NULL UNIQUE,
    "Super_Admin"   BOOLEAN DEFAULT 0,
    "Deleted_At"    int,
    FOREIGN KEY ("Domain_ID") REFERENCES "Domain" ("ID") ON DELETE CASCADE ON UPDATE CASCADE
);
DROP TABLE IF EXISTS "PermissionMap";
CREATE TABLE IF NOT EXISTS "PermissionMap"
(
    "ID"           INTEGER PRIMARY KEY AUTOINCREMENT,
    "Ref_ID"       STRING  NOT NULL UNIQUE,
    "Privilege_ID" INTEGER NOT NULL,
    "OU"           STRING,
    "User_Perm"    INTEGER DEFAULT 0,
    "Group_Perm"   INTEGER DEFAULT 0,
    "Deleted_At"   INTEGER,
    FOREIGN KEY ("Privilege_ID") REFERENCES "PrivilegeLevel" on update cascade on delete cascade
);
DROP TABLE IF EXISTS "Email";
CREATE TABLE IF NOT EXISTS "Email"
(
    "ID"                  INTEGER DEFAULT 1 PRIMARY KEY AUTOINCREMENT,
    "From_Address"        STRING,
    "From_Name"           STRING,
    "Reply_To_Address"    STRING,
    "Reply_To_Name"       STRING,
    "Use_SMTP_Encryption" INTEGER,
    "SMTP_Server"         STRING,
    "SMTP_Port"           INTEGER,
    "Use_SMTP_Auth"       BOOLEAN,
    "SMTP_Username"       STRING,
    "SMTP_Password"       STRING
);
DROP TABLE IF EXISTS "Auth";
CREATE TABLE IF NOT EXISTS "Auth"
(
    "ID"              INTEGER,
    "Admin_Password"  STRING  NOT NULL DEFAULT '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',
    "LDAP_Enabled"    BOOLEAN          DEFAULT 0,
    "Session_Timeout" INTEGER NOT NULL DEFAULT 1200,
    PRIMARY KEY ("ID")
);
DROP TABLE IF EXISTS "Audit";
CREATE TABLE IF NOT EXISTS "Audit"
(
    "ID"          INTEGER,
    "Timestamp"   DATETIME DEFAULT (SYSDATETIME()),
    "Username"    TEXT,
    "IP"          TEXT,
    "Action"      TEXT,
    "Description" TEXT,
    PRIMARY KEY ("ID")
);

-- Trigger: init
DROP TRIGGER IF EXISTS init;
CREATE TRIGGER init

    AFTER INSERT

    ON App

BEGIN

    INSERT INTO Auth (ID) VALUES (1);

    INSERT INTO Email (ID) VALUES (1);

END;

COMMIT TRANSACTION;
PRAGMA foreign_keys = on;
