BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "App"
(
    "ID"                       TEXT             DEFAULT 1,
    "Name"                     STRING  NOT NULL DEFAULT 'Active Directory Accounts Manager',
    "Force_HTTPS"              BOOLEAN NOT NULL DEFAULT 0,
    "MOTD"                     TEXT,
    "Debug_Mode"               BOOLEAN NOT NULL DEFAULT 0,
    "Websitie_FQDN"            STRING,
    "Database_Version"         STRING           DEFAULT '0.1.0',
    "User_Helpdesk_URL"        STRING,
    "Update_Check_URL"         STRING           DEFAULT 'https://raw.githubusercontent.com/jacobsen9026/AD-Accounts-Manager/master/update',
    "Setup_Completed"          BOOLEAN          DEFAULT 0,
    "Last_Update_Check"        int              DEFAULT 0,
    "Latest_Available_Version" STRING,
    PRIMARY KEY ("ID"),
    CHECK (ID = 1)
);
CREATE TABLE IF NOT EXISTS "District"
(
    "ID"                      INTEGER DEFAULT 1 PRIMARY KEY AUTOINCREMENT,
    "Name"                    TEXT NOT NULL UNIQUE,
    "Abbreviation"            STRING,
    "AD_FQDN"                 STRING,
    "AD_Server"               STRING,
    "AD_BaseDN"               STRING,
    "AD_NetBIOS"              STRING,
    "AD_Username"             STRING,
    "AD_Password"             STRING,
    "AD_Student_Group"        STRING,
    "GA_FQDN"                 STRING,
    "Parent_Email_Group"      STRING,
    "Staff_Username_Format"   STRING,
    "Student_Username_Format" STRING,
    "AD_Use_TLS"              BOOLEAN DEFAULT 0,
    CHECK (ID = 1)
);
CREATE TABLE IF NOT EXISTS "Auth"
(
    "ID"              INTEGER,
    "Admin_Password"  STRING  NOT NULL DEFAULT '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',
    "LDAP_Enabled"    BOOLEAN          DEFAULT 0,
    "Session_Timeout" INTEGER NOT NULL DEFAULT 1200,
    PRIMARY KEY ("ID")
);
CREATE TABLE IF NOT EXISTS "Email"
(
    "ID"                    INTEGER DEFAULT 1 PRIMARY KEY AUTOINCREMENT,
    "From_Address"          STRING,
    "From_Name"             STRING,
    "Admin_Email_Addresses" STRING,
    "Welcome_Email_BCC"     STRING,
    "Welcome_Email"         STRING,
    "Reply_To_Address"      STRING,
    "Reply_To_Name"         STRING,
    "Use_SMTP_SSL"          BOOLEAN,
    "SMTP_Server"           STRING,
    "SMTP_Port"             INTEGER,
    "Use_SMTP_Auth"         BOOLEAN,
    "SMTP_Username"         STRING,
    "SMTP_Password"         STRING
);
CREATE TABLE IF NOT EXISTS "PermissionMap"
(
    "ID"           INTEGER PRIMARY KEY AUTOINCREMENT,
    "Ref_ID"       STRING  NOT NULL UNIQUE,
    "Privilege_ID" INTEGER NOT NULL,
    "OU"           STRING,
    "User_Perm"    INTEGER DEFAULT 0,
    "Group_Perm"   INTEGER DEFAULT 0,
    FOREIGN KEY ("Privilege_ID") REFERENCES "PrivilegeLevel" ("ID") ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE IF NOT EXISTS "PrivilegeLevel"
(
    "ID"            INTEGER PRIMARY KEY AUTOINCREMENT,
    "District_ID"   INTEGER,
    "AD_Group_Name" STRING NOT NULL UNIQUE,
    "Super_Admin"   BOOLEAN DEFAULT 0,
    FOREIGN KEY ("District_ID") REFERENCES "District" ("ID") ON DELETE CASCADE ON UPDATE CASCADE
);
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
CREATE TABLE IF NOT EXISTS "User"
(
    "ID"        INTEGER PRIMARY KEY AUTOINCREMENT,
    "Username"  STRING UNIQUE,
    "Token"     STRING UNIQUE,
    "Theme"     STRING,
    "Privilege" INTEGER DEFAULT 0
);
CREATE TABLE IF NOT EXISTS "GradeDefinition"
(
    "Value"        INTEGER,
    "Display_Code" STRING,
    "Display_Name" STRING,
    PRIMARY KEY ("Value")
);
COMMIT;
