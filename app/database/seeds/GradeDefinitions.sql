--
-- File generated with SQLiteStudio v3.2.1 on Mon Dec 16 10:23:14 2019
--
-- Text encoding used: System
--
PRAGMA foreign_keys = off;
BEGIN TRANSACTION;
DROP TABLE IF EXISTS GradeDefinition;
-- Table: GradeDefinition
CREATE TABLE GradeDefinition (Value INTEGER PRIMARY KEY, Display_Code STRING, Display_Name STRING);
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (-2, 'PK3', 'Pre-Kindergarten 3Yr');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (-1, 'PK4', 'Pre-Kindergarten 4Yr');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (0, 'K', 'Kindergarten');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (1, 1, 'Grade 1');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (2, 2, 'Grade 2');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (3, 3, 'Grade 3');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (4, 4, 'Grade 4');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (5, 5, 'Grade 5');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (6, 6, 'Grade 6');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (7, 7, 'Grade 7');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (8, 8, 'Grade 8');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (9, 9, 'Freshman');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (10, 10, 'Sophmore');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (11, 11, 'Junior');
INSERT INTO GradeDefinition (Value, Display_Code, Display_Name) VALUES (12, 12, 'Senior');

COMMIT TRANSACTION;
PRAGMA foreign_keys = on;
