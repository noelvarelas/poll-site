# Description
A poll site where every visitor gets asked the same controversial question once per day.

# Server Setup
Designed with AlmaLinux, Apache (httpd), MariaDB, and PHP in mind.
There is a [script](https://github.com/noelvarelas/poll-site/blob/main/setup/almalinux-setup.sh) that automates the setup process.

# Features to work on
- Display results of previous day on the main page
- Separate results page to view all past questions
- Suggestions box that appends one line to a private text file on the server

# Things to fix
- Put the database connection credentials in a git-ignored config file, then make a public config example file