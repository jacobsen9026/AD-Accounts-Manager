<?php

namespace app\database;

class Schema
{

    const NAME = 'name';
    const TABLE = 'table';
    const COLUMN = 'column';
    const ACTIVEDIRECTORY = 'ActiveDirectory';
    const APP = 'App';
    const AUTH = 'Auth';
    const DISTRICT = 'District';
    const DEPARTMENT = 'Department';
    const EMAIL = 'Email';
    const GOOGLEAPPS = 'GoogleApps';
    const GRADE = 'Grade';
    const GRADEDEFINITION = 'GradeDefinition';
    const LOGON = 'Logon';
    const SCHOOL = 'School';
    const USER = 'Session';
    const TEAM = 'Team';
    const ACTIVEDIRECTORY_ID = ['table' => 'ActiveDirectory', 'column' => 'ID', 'name' => 'ActiveDirectory_ID'];
    const ACTIVEDIRECTORY_DISTRICT_ID = ['table' => 'ActiveDirectory', 'column' => 'District_ID', 'name' => 'ActiveDirectory_District_ID'];
    const ACTIVEDIRECTORY_SCHOOL_ID = ['table' => 'ActiveDirectory', 'column' => 'School_ID', 'name' => 'ActiveDirectory_School_ID'];
    const ACTIVEDIRECTORY_DEPARTMENT_ID = ['table' => 'ActiveDirectory', 'column' => 'Department_ID', 'name' => 'ActiveDirectory_Department_ID'];
    const ACTIVEDIRECTORY_GRADE_ID = ['table' => 'ActiveDirectory', 'column' => 'Grade_ID', 'name' => 'ActiveDirectory_Grade_ID'];
    const ACTIVEDIRECTORY_TEAM_ID = ['table' => 'ActiveDirectory', 'column' => 'Team_ID', 'name' => 'ActiveDirectory_Team_ID'];
    const ACTIVEDIRECTORY_TYPE = ['table' => 'ActiveDirectory', 'column' => 'Type', 'name' => 'ActiveDirectory_Type'];
    const ACTIVEDIRECTORY_OU = ['table' => 'ActiveDirectory', 'column' => 'OU', 'name' => 'ActiveDirectory_OU'];
    const ACTIVEDIRECTORY_GROUP = ['table' => 'ActiveDirectory', 'column' => 'Group', 'name' => 'ActiveDirectory_Group'];
    const ACTIVEDIRECTORY_HOME_PATH = ['table' => 'ActiveDirectory', 'column' => 'Home_Path', 'name' => 'ActiveDirectory_Home_Path'];
    const ACTIVEDIRECTORY_LOGON_SCRIPT = ['table' => 'ActiveDirectory', 'column' => 'Logon_Script', 'name' => 'ActiveDirectory_Logon_Script'];
    const ACTIVEDIRECTORY_DESCRIPTION = ['table' => 'ActiveDirectory', 'column' => 'Description', 'name' => 'ActiveDirectory_Description'];
    const ACTIVEDIRECTORY_FORCE_PASSWORD_CHANGE = ['table' => 'ActiveDirectory', 'column' => 'Force_Password_Change', 'name' => 'ActiveDirectory_Force_Password_Change'];

    /**
     * const APP_ID = array('table' => 'App', 'column' => 'ID', 'name' => 'App_ID');
     * const APP_NAME = array('table' => 'App', 'column' => 'Name', 'name' => 'App_Name');
     * const APP_FORCE_HTTPS = array('table' => 'App', 'column' => 'Force_HTTPS', 'name' => 'App_Force_HTTPS');
     * const APP_MOTD = array('table' => 'App', 'column' => 'MOTD', 'name' => 'App_MOTD');
     * const APP_DEBUG_MODE = array('table' => 'App', 'column' => 'Debug_Mode', 'name' => 'App_Debug_Mode');
     * const APP_PROTECTED_ADMIN_USERNAMES = array('table' => 'App', 'column' => 'Protected_Admin_Usernames', 'name' =>
     * 'App_Protected_Admin_Usernames'); const APP_WEBSITIE_FQDN = array('table' => 'App', 'column' => 'Websitie_FQDN',
     * 'name' => 'App_Websitie_FQDN'); const APP_APP_VERSION = array('table' => 'App', 'column' => 'App_Version',
     * 'name' => 'App_App_Version'); const APP_DATABASE_VERSION = array('table' => 'App', 'column' =>
     * 'Database_Version', 'name' => 'App_Database_Version'); const APP_USER_HELPDESK_URL = array('table' => 'App',
     * 'column' => 'User_Helpdesk_URL', 'name' => 'App_User_Helpdesk_URL'); const APP_UPDATE_CHECK_URL = array('table'
     * => 'App', 'column' => 'Update_Check_URL', 'name' => 'App_Update_Check_URL');
     *
     */
    const APP_ADMIN_PASSWORD = ['table' => 'App', 'column' => 'Admin_Password', 'name' => 'App_Admin_Password'];
    const DEPARTMENT_ID = ['table' => 'Department', 'column' => 'ID', 'name' => 'Department_ID'];
    const DEPARTMENT_SCHOOL_ID = ['table' => 'Department', 'column' => 'School_ID', 'name' => 'Department_School_ID'];
    const DEPARTMENT_NAME = ['table' => 'Department', 'column' => 'Name', 'name' => 'Department_Name'];
    const EMAIL_ID = ['table' => 'Email', 'column' => 'ID', 'name' => 'Email_ID'];
    const EMAIL_APP_ID = ['table' => 'Email', 'column' => 'App_ID', 'name' => 'Email_App_ID'];
    const EMAIL_FROM_ADDRESS = ['table' => 'Email', 'column' => 'From_Address', 'name' => 'Email_From_Address'];
    const EMAIL_FROM_NAME = ['table' => 'Email', 'column' => 'From_Name', 'name' => 'Email_From_Name'];
    const EMAIL_ADMIN_EMAIL_ADDRESSES = ['table' => 'Email', 'column' => 'Admin_Email_Addresses', 'name' => 'Email_Admin_Email_Addresses'];
    const EMAIL_WELCOME_EMAIL_BCC = ['table' => 'Email', 'column' => 'Welcome_Email_BCC', 'name' => 'Email_Welcome_Email_BCC'];
    const EMAIL_WELCOME_EMAIL = ['table' => 'Email', 'column' => 'Welcome_Email', 'name' => 'Email_Welcome_Email'];
    const EMAIL_REPLY_TO_ADDRESS = ['table' => 'Email', 'column' => 'Reply_To_Address', 'name' => 'Email_Reply_To_Address'];
    const EMAIL_REPLY_TO_NAME = ['table' => 'Email', 'column' => 'Reply_To_Name', 'name' => 'Email_Reply_To_Name'];
    const EMAIL_USE_SMTP_SSL = ['table' => 'Email', 'column' => 'Use_SMTP_SSL', 'name' => 'Email_Use_SMTP_SSL'];
    const EMAIL_SMTP_SERVER = ['table' => 'Email', 'column' => 'SMTP_Server', 'name' => 'Email_SMTP_Server'];
    const EMAIL_SMTP_PORT = ['table' => 'Email', 'column' => 'SMTP_Port', 'name' => 'Email_SMTP_Port'];
    const EMAIL_USE_SMTP_AUTH = ['table' => 'Email', 'column' => 'Use_SMTP_Auth', 'name' => 'Email_Use_SMTP_Auth'];
    const EMAIL_SMTP_USERNAME = ['table' => 'Email', 'column' => 'SMTP_Username', 'name' => 'Email_SMTP_Username'];
    const EMAIL_SMTP_PASSWORD = ['table' => 'Email', 'column' => 'SMTP_Password', 'name' => 'Email_SMTP_Password'];
    const GOOGLEAPPS_ID = ['table' => 'GoogleApps', 'column' => 'ID', 'name' => 'GoogleApps_ID'];
    const GOOGLEAPPS_SCHOOL_ID = ['table' => 'GoogleApps', 'column' => 'School_ID', 'name' => 'GoogleApps_School_ID'];
    const GOOGLEAPPS_DISTRICT_ID = ['table' => 'GoogleApps', 'column' => 'District_ID', 'name' => 'GoogleApps_District_ID'];
    const GOOGLEAPPS_DEPARTMENT_ID = ['table' => 'GoogleApps', 'column' => 'Department_ID', 'name' => 'GoogleApps_Department_ID'];
    const GOOGLEAPPS_GRADE_ID = ['table' => 'GoogleApps', 'column' => 'Grade_ID', 'name' => 'GoogleApps_Grade_ID'];
    const GOOGLEAPPS_TEAM_ID = ['table' => 'GoogleApps', 'column' => 'Team_ID', 'name' => 'GoogleApps_Team_ID'];
    const GOOGLEAPPS_TYPE = ['table' => 'GoogleApps', 'column' => 'Type', 'name' => 'GoogleApps_Type'];
    const GOOGLEAPPS_OU = ['table' => 'GoogleApps', 'column' => 'OU', 'name' => 'GoogleApps_OU'];
    const GOOGLEAPPS_GROUP = ['table' => 'GoogleApps', 'column' => 'Group', 'name' => 'GoogleApps_Group'];
    const GOOGLEAPPS_USERNAME_FORMAT = ['table' => 'GoogleApps', 'column' => 'Username_Format', 'name' => 'GoogleApps_Username_Format'];
    const GOOGLEAPPS_OTHER_GROUPS = ['table' => 'GoogleApps', 'column' => 'Other_Groups', 'name' => 'GoogleApps_Other_Groups'];
    const GOOGLEAPPS_FORCE_PASSWORD_CHANGE = ['table' => 'GoogleApps', 'column' => 'Force_Password_Change', 'name' => 'GoogleApps_Force_Password_Change'];
    const GRADE_ID = ['table' => 'Grade', 'column' => 'ID', 'name' => 'Grade_ID'];
    const GRADE_SCHOOL_ID = ['table' => 'Grade', 'column' => 'School_ID', 'name' => 'Grade_School_ID'];
    const GRADE_GRADE_DEFINITION_ID = ['table' => 'Grade', 'column' => 'Grade_Definition_ID', 'name' => 'Grade_Grade_Definition_ID'];
    const GRADE_PARENT_EMAIL_GROUP = ['table' => 'Grade', 'column' => 'Parent_Email_Group', 'name' => 'Grade_Parent_Email_Group'];
    const LOGON_ID = ['table' => 'Logon', 'column' => 'ID', 'name' => 'Logon_ID'];
    const LOGON_TIMESTAMP = ['table' => 'Logon', 'column' => 'Timestamp', 'name' => 'Logon_Timestamp'];
    const LOGON_USERNAME = ['table' => 'Logon', 'column' => 'Username', 'name' => 'Logon_Username'];
    const SCHOOL_ID = ['table' => 'School', 'column' => 'ID', 'name' => 'School_ID'];
    const SCHOOL_DISTRICT_ID = ['table' => 'School', 'column' => 'District_ID', 'name' => 'School_District_ID'];
    const SCHOOL_NAME = ['table' => 'School', 'column' => 'Name', 'name' => 'School_Name'];
    const SCHOOL_OU = ['table' => 'School', 'column' => 'OU', 'name' => 'School_OU'];
    const SCHOOL_ABBREVIATION = ['table' => 'School', 'column' => 'Abbreviation', 'name' => 'School_Abbreviation'];
    const SCHOOL_PARENT_EMAIL_GROUP = ['table' => 'School', 'column' => 'Parent_Email_Group', 'name' => 'School_Parent_Email_Group'];
    const USER_ID = ['table' => 'Session', 'column' => 'ID', 'name' => 'Session_ID'];
    const USER_LAST_AUTHENTICATED = ['table' => 'Session', 'column' => 'Last_Authenticated', 'name' => 'Session_Last_Authenticated'];
    const USER_USER_OBJECT = ['table' => 'Session', 'column' => 'User_Object', 'name' => 'Session_User_Object'];
    const USER_TOKEN = ['table' => 'Session', 'column' => 'Token', 'name' => 'Session_Token'];
    const TEAM_ID = ['table' => 'Team', 'column' => 'ID', 'name' => 'Team_ID'];
    const TEAM_GRADE_ID = ['table' => 'Team', 'column' => 'Grade_ID', 'name' => 'Team_Grade_ID'];
    const TEAM_NAME = ['table' => 'Team', 'column' => 'Name', 'name' => 'Team_Name'];
    const TEAM_PARENT_EMAIL_GROUP = ['table' => 'Team', 'column' => 'Parent_Email_Group', 'name' => 'Team_Parent_Email_Group'];
    const GRADEDEFINITION_VALUE = ['table' => 'GradeDefinition', 'column' => 'Value', 'name' => 'GradeDefinition_Value'];
    const GRADEDEFINITION_DISPLAY_CODE = ['table' => 'GradeDefinition', 'column' => 'Display_Code', 'name' => 'GradeDefinition_Display_Code'];
    const GRADEDEFINITION_DISPLAY_NAME = ['table' => 'GradeDefinition', 'column' => 'Display_Name', 'name' => 'GradeDefinition_Display_Name'];
    const AUTH_ID = ['table' => 'Auth', 'column' => 'ID', 'name' => 'Auth_ID'];
    const AUTH_APP_ID = ['table' => 'Auth', 'column' => 'App_ID', 'name' => 'Auth_App_ID'];
    const AUTH_TECH_AD_GROUP = ['table' => 'Auth', 'column' => 'Tech_AD_Group', 'name' => 'Auth_Tech_AD_Group'];
    const AUTH_ADMIN_AD_GROUP = ['table' => 'Auth', 'column' => 'Admin_AD_Group', 'name' => 'Auth_Admin_AD_Group'];
    const AUTH_POWER_AD_GROUP = ['table' => 'Auth', 'column' => 'Power_AD_Group', 'name' => 'Auth_Power_AD_Group'];
    const AUTH_BASIC_AD_GROUP = ['table' => 'Auth', 'column' => 'Basic_AD_Group', 'name' => 'Auth_Basic_AD_Group'];
    const AUTH_TECH_GA_GROUP = ['table' => 'Auth', 'column' => 'Tech_GA_Group', 'name' => 'Auth_Tech_GA_Group'];
    const AUTH_ADMIN_GA_GROUP = ['table' => 'Auth', 'column' => 'Admin_GA_Group', 'name' => 'Auth_Admin_GA_Group'];
    const AUTH_POWER_GA_GROUP = ['table' => 'Auth', 'column' => 'Power_GA_Group', 'name' => 'Auth_Power_GA_Group'];
    const AUTH_BASIC_GA_GROUP = ['table' => 'Auth', 'column' => 'Basic_GA_Group', 'name' => 'Auth_Basic_GA_Group'];
    const AUTH_LDAP_ENABLED = ['table' => 'Auth', 'column' => 'LDAP_Enabled', 'name' => 'Auth_LDAP_Enabled'];
    const AUTH_LDAP_SERVER = ['table' => 'Auth', 'column' => 'LDAP_Server', 'name' => 'Auth_LDAP_Server'];
    const AUTH_LDAP_FQDN = ['table' => 'Auth', 'column' => 'LDAP_FQDN', 'name' => 'Auth_LDAP_FQDN'];
    const AUTH_LDAP_PORT = ['table' => 'Auth', 'column' => 'LDAP_Port', 'name' => 'Auth_LDAP_Port'];
    const AUTH_LDAP_USERNAME = ['table' => 'Auth', 'column' => 'LDAP_Username', 'name' => 'Auth_LDAP_Username'];
    const AUTH_LDAP_PASSWORD = ['table' => 'Auth', 'column' => 'LDAP_Password', 'name' => 'Auth_LDAP_Password'];
    const AUTH_LDAP_USE_SSL = ['table' => 'Auth', 'column' => 'LDAP_Use_SSL', 'name' => 'Auth_LDAP_Use_SSL'];
    const AUTH_OAUTH_ENABLED = ['table' => 'Auth', 'column' => 'OAuth_Enabled', 'name' => 'Auth_OAuth_Enabled'];
    const AUTH_SESSION_TIMEOUT = ['table' => 'Auth', 'column' => 'Session_Timeout', 'name' => 'Auth_Session_Timeout'];
    const DISTRICT_ID = ['table' => 'District', 'column' => 'ID', 'name' => 'District_ID'];
    const DISTRICT_APP_ID = ['table' => 'District', 'column' => 'App_ID', 'name' => 'District_App_ID'];
    const DISTRICT_USING_GADS = ['table' => 'District', 'column' => 'Using_GADS', 'name' => 'District_Using_GADS'];
    const DISTRICT_USING_GAPS = ['table' => 'District', 'column' => 'Using_GAPS', 'name' => 'District_Using_GAPS'];
    const DISTRICT_NAME = ['table' => 'District', 'column' => 'Name', 'name' => 'District_Name'];
    const DISTRICT_GRADE_SPAN_FROM = ['table' => 'District', 'column' => 'Grade_Span_From', 'name' => 'District_Grade_Span_From'];
    const DISTRICT_GRADE_SPAN_TO = ['table' => 'District', 'column' => 'Grade_Span_To', 'name' => 'District_Grade_Span_To'];
    const DISTRICT_ABBREVIATION = ['table' => 'District', 'column' => 'Abbreviation', 'name' => 'District_Abbreviation'];
    const DISTRICT_AD_FQDN = ['table' => 'District', 'column' => 'AD_FQDN', 'name' => 'District_AD_FQDN'];
    const DISTRICT_AD_SERVER = ['table' => 'District', 'column' => 'AD_Server', 'name' => 'District_AD_Server'];
    const DISTRICT_AD_NETBIOS = ['table' => 'District', 'column' => 'AD_NetBIOS', 'name' => 'District_AD_NetBIOS'];
    const DISTRICT_AD_BASEDN = ['table' => 'District', 'column' => 'AD_BaseDN', 'name' => 'District_AD_BaseDN'];
    const DISTRICT_AD_USERNAME = ['table' => 'District', 'column' => 'AD_Username', 'name' => 'District_AD_Username'];
    const DISTRICT_AD_PASSWORD = ['table' => 'District', 'column' => 'AD_Password', 'name' => 'District_AD_Password'];
    const DISTRICT_AD_STUDENT_GROUP = ['table' => 'District', 'column' => 'AD_Student_Group', 'name' => 'District_AD_Student_Group'];
    const DISTRICT_AD_STAFF_GROUP = ['table' => 'District', 'column' => 'AD_Staff_Group', 'name' => 'District_AD_Staff_Group'];
    const DISTRICT_GA_FQDN = ['table' => 'District', 'column' => 'GA_FQDN', 'name' => 'District_GA_FQDN'];
    const DISTRICT_PARENT_EMAIL_GROUP = ['table' => 'District', 'column' => 'Parent_Email_Group', 'name' => 'District_Parent_Email_Group'];
    const DISTRICT_STAFF_USERNAME_FORMAT = ['table' => 'District', 'column' => 'Staff_Username_Format', 'name' => 'District_Staff_Username_Format'];
    const DISTRICT_STUDENT_USERNAME_FORMAT = ['table' => 'District', 'column' => 'Student_Username_Format', 'name' => 'District_Student_Username_Format'];
    const PERMISSION_ID = ['table' => 'Permission', 'column' => 'ID', 'name' => 'Permission_ID'];
    const PERMISSION_REQUIRED_PERMISSION = ['table' => 'Permission', 'column' => 'Required_Permission', 'name' => 'Permission_Required_Permission'];
    const PERMISSION_PATH = ['table' => 'Permission', 'column' => 'Path', 'name' => 'Permission_Path'];

}
