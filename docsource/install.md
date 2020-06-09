# Installation
!!! info
    This information is very preliminary. There may be missing steps or incorrect instructions.
    
## Web Server Setup
- URL rewriting must be enabled and pointing to `/public/index.php`
### Windows
#### WAMP
##### Install WAMP
1. Download the latest Wamp package from [SourceForge](https://sourceforge.net/projects/wampserver/)
1. Installing Wamp with the default settings is usually enough for most environments. If your environment is more complicated or has other web servers on the same machine you should customize the installation to suit.
##### Pre-Configure WAMP
1. After installation. Ensure that version 7.4+ is being used and that the following extensions are enabled.
    - ldap
    - pdo_sqlite
    - sqlite3
1. (Optional) Configure VHost if you have a specific directory or URL in mind.
##### Install ADAM
1. Download the latest release from [GitHub](https://github.com/jacobsen9026/AD-Accounts-Manager/releases)
1. Unzip the file to the website root directory
1. Go ahead and open up your browser to the url you expect this website should be served from.
1. If you reach the login page then no further configuration is required.
##### Post-Configure WAMP
*If you did not get the login page, or you do not want to rely on .htaccess files. You will need to complete these steps*

1. Configure VHost
#### XAMPP
##### Install XAMPP
1. Download the latest Wamp package from [Apache Friends](https://www.apachefriends.org/index.html)
1. Installing Wamp with the default settings is usually enough for most environments. If your environment is more complicated or has other web servers on the same machine you should customize the installation to suit.
##### Pre-Configure XAMPP
1. After installation. Ensure that version 7.4+ is being used and that the following extensions are enabled.
    - ldap
    - pdo_sqlite
    - sqlite3
1. (Optional) Configure VHost if you have a specific directory or URL in mind.
##### Install ADAM
1. Download the latest release from [GitHub](https://github.com/jacobsen9026/AD-Accounts-Manager/releases)
1. Unzip the file to the website root directory
1. Go ahead and open up your browser to the url you expect this website should be served from.
1. If you reach the login page then no further configuration is required.
##### Post-Configure XAMPP
*If you did not get the login page, or you do not want to rely on .htaccess files. You will need to complete these steps*

1. Configure VHost
### CentOS

### Ubuntu

