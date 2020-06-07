alter table Email
	add 'Use_SMTP_Encyption' int;

UPDATE Email SET 'Use_SMTP_Encyption'= (SELECT Use_SMTP_SSL FROM Email WHERE 'ID'=1) WHERE 'ID'=1;

create table Email_dg_tmp
(
	ID INTEGER default 1
		primary key autoincrement,
	From_Address STRING,
	From_Name STRING,
	Admin_Email_Addresses STRING,
	Welcome_Email_BCC STRING,
	Welcome_Email STRING,
	Reply_To_Address STRING,
	Reply_To_Name STRING,
	SMTP_Server STRING,
	SMTP_Port INTEGER,
	Use_SMTP_Auth BOOLEAN,
	Use_SMTP_Encyption int,
	SMTP_Username STRING,
	SMTP_Password STRING
);

insert into Email_dg_tmp(ID, From_Address, From_Name, Admin_Email_Addresses, Welcome_Email_BCC, Welcome_Email, Reply_To_Address, Reply_To_Name, SMTP_Server, SMTP_Port, Use_SMTP_Auth, SMTP_Username, SMTP_Password, Use_SMTP_Encyption) select ID, From_Address, From_Name, Admin_Email_Addresses, Welcome_Email_BCC, Welcome_Email, Reply_To_Address, Reply_To_Name, SMTP_Server, SMTP_Port, Use_SMTP_Auth, SMTP_Username, SMTP_Password, Use_SMTP_Encyption from Email;

drop table Email;

alter table Email_dg_tmp rename to Email;