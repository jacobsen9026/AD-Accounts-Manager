create table PermissionMap_dg_tmp
(
    ID           INTEGER
        primary key autoincrement,
    Ref_ID       STRING  not null
        unique,
    Privilege_ID INTEGER not null
        references PrivilegeLevel
            on update cascade on delete cascade,
    DN           STRING,
    User_Perm    INTEGER default 0,
    Group_Perm   INTEGER default 0
);

insert into PermissionMap_dg_tmp(ID, Ref_ID, Privilege_ID, DN, User_Perm, Group_Perm)
select ID, Ref_ID, Privilege_ID, OU, User_Perm, Group_Perm
from PermissionMap;

drop table PermissionMap;

alter table PermissionMap_dg_tmp
    rename to PermissionMap;

alter table PermissionMap
    add Deleted_At INTEGER;

alter table PrivilegeLevel
    add Deleted_At int;

UPDATE Schema
SET Schema_Version="0.1.2"
WHERE ID = 1;