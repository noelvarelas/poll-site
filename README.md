# Description
A poll site where every visitor gets asked the same controversial question once per day.

# Server Setup
Designed with AlmaLinux, Apache (httpd), MariaDB, and PHP in mind.
There is a [setup script](https://github.com/noelvarelas/poll-site/blob/main/setup/almalinux-setup.sh) that automates the setup process. There is also an [add question script](https://github.com/noelvarelas/poll-site/blob/main/setup/add-question.sh) for convenience during testing.

# Features to work on
- Display results of previous day on the main page
- Separate results page to view all past questions
- Suggestions box that appends one line to a private text file on the server

# Things to fix
- 