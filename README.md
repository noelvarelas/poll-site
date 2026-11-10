# Description
A poll site where every visitor gets asked the same controversial question once per day.

# Server Setup
There is a [setup script](https://github.com/noelvarelas/poll-site/blob/main/setup/alpine-setup.sh) that will fully configure a fresh Alpine Linux installation with the site.
It is still up to the user to set up TLS/HTTPS, as well as secure their server beyond the firewall provided if desired.
Otherwise, the site can be set up manually on any web server using SQLite and PHP.

# To do
- Make site not hideous