# Description
A poll site where every visitor gets asked the same controversial question once per day.

# Server Setup
Detailed instructions to come. Designed with AlmaLinux, Apache (httpd), MariaDB, and PHP in mind. The web root is `/var/www/poll_site/public`

# Features to work on
- Display results of previous day on the main page.
- Seperate results page to view all past questions.
- Suggestions box that appends one line to a private text file on the server.

# Things to fix
- Put the database connection credentials in a git-ignored file, then make a public config example file.