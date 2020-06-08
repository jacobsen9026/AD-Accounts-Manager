# Permissions
## Privilge Levels
Privilege Levels are essentially Active Directory groups that are allowed to log into the application.

By being in one of the groups assigned a Privilege Level, a user can sign into the application.

Setting a group as super user is only advisable for IT staff.

!!! question "Changing Groups"
    If a group name changes, or you want to move a set of permissions to another group, you can simply rename the Privilege
    Level group name.
!!! danger "Deleting"
    At the current state deleting a Privilege Level removes all mapped permissions and permanently deletes the Privilege Level.
    In a future update this will be improved to allow recovery. 

## OU Permission Navigator

![ou level permission](../images/ou-perm-explain.png)

1. Total number of OU level permissions applied for entire application
1. An OU highlighted green indicates that a sub-OU has permissions applied beneath it.
1. Number of permissions applied to this particular OU
1. The distinguished name of the OU for the currently displayed permissions
1. The user permission level this Privilege Level has for this OU.
1. The group permission level this Privilege Level has for this OU.


!!! note
    User and group permissions apply at the current OU and all child OU's unless explicitly set lower.
    
!!! question "Blocking Inheritance"
    To stop inheritance at a certain OU level, add a permission with "None" set.