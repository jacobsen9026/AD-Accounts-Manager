# Apache
## Install Apache
# Pre-Configure Apache

## Install ADAM
1. Download the latest release from [GitHub](https://github.com/jacobsen9026/AD-Accounts-Manager/releases)
1. Unzip the file to the website root directory
## Post-Configure Apache
Before we can start the app we need to give it permission via SELinux to allow creation of 
folders. This is done with the following command:
`chcon -R -t httpd_sys_content_rw_t /var/www/html`

If you installed the app in a different location change as needed.