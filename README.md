# Description
A poll site where every visitor gets asked the same controversial question once per day.

# Server Setup
Designed with AlmaLinux, Apache (httpd), MariaDB, and PHP in mind.
There is a [setup script](https://github.com/noelvarelas/poll-site/blob/main/setup/almalinux-setup.sh) that automates the setup process.
There is also an [add question script](https://github.com/noelvarelas/poll-site/blob/main/setup/add-question.sh) for convenience during testing.

# To do
- Migrate server setup script to Alpine/lighttpd/SQLite/PHP
- Make site not hideous