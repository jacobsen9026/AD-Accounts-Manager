PRAGMA foreign_keys = 0;

CREATE TABLE sqlitestudio_temp_table AS SELECT *
                                          FROM App;

DROP TABLE App;

CREATE TABLE App (
    ID                       TEXT    DEFAULT 1,
    Name                     STRING  NOT NULL
                                     DEFAULT 'Active Directory Accounts Manager',
    Force_HTTPS              BOOLEAN NOT NULL
                                     DEFAULT 0,
    MOTD                     TEXT,
    Debug_Mode               BOOLEAN NOT NULL
                                     DEFAULT 0,
    Websitie_FQDN            STRING,
    User_Helpdesk_URL        STRING,
    Update_Check_URL         STRING  DEFAULT 'https://raw.githubusercontent.com/jacobsen9026/AD-Accounts-Manager/master/update',
    Setup_Completed          BOOLEAN DEFAULT 0,
    Last_Update_Check        INT     DEFAULT 0,
    Latest_Available_Version STRING,
    PRIMARY KEY (
        ID
    ),
    CHECK (ID = 1)
);

INSERT INTO App (
                    ID,
                    Name,
                    Force_HTTPS,
                    MOTD,
                    Debug_Mode,
                    Websitie_FQDN,
                    User_Helpdesk_URL,
                    Update_Check_URL,
                    Setup_Completed,
                    Last_Update_Check,
                    Latest_Available_Version
                )
                SELECT ID,
                       Name,
                       Force_HTTPS,
                       MOTD,
                       Debug_Mode,
                       Websitie_FQDN,
                       User_Helpdesk_URL,
                       Update_Check_URL,
                       Setup_Completed,
                       Last_Update_Check,
                       Latest_Available_Version
                  FROM sqlitestudio_temp_table;

DROP TABLE sqlitestudio_temp_table;

CREATE TRIGGER init
         AFTER INSERT
            ON App
BEGIN
    INSERT INTO Auth (
                         ID
                     )
                     VALUES (
                         1
                     );
    INSERT INTO Email (
                          ID
                      )
                      VALUES (
                          1
                      );
END;

PRAGMA foreign_keys = 1;

UPDATE Schema
SET Schema_Version="0.1.3"
WHERE ID = 1;