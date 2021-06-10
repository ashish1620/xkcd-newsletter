# xkcd-newsletter
A simple mailing service to send xkcd comics to the registered users

## Features
* Register your mail id
* Receive random xkcd comic
* Unsubscribe when not needed


## Libs used
* All basic level php libs
* MySQL for storage of emails
* HTTP GET, POST for data transfer
* Mail service, to send respective mails
* Cron job for running the mailing service

## Requries postfix
* Set up for the mail

```console
# for gmail to work you need to allow access to less secure apps on google account
 
$ sudo apt install postfix
# select internet as the option in the prompt

$ sudo apt install mailutils

# edit the postfix conf file
$ sudo vim /etc/postfix/main.cf

# change these lines
# (add this below smtp_tls_CApath=/etc/ssl/certs)
smtp_tls_security_level=encrypt 

#find relay host add 
relayhost = [smtp.gmail.com]:587

# add theses to bottom
smtp_sasl_auth_enable = yes
smtp_sasl_password_maps = hash:/etc/postfix/sasl_passwd
smtp_sasl_security_options = noanonymous

# add this to sasl_passwd
$ sudo vim /etc/postfix/sasl_passwd

# add this line 
[smtp.gmail.com]:587 email@gmail.com:password

# change ownership and mode
$ sudo chown root:root /etc/postfix/sasl_passwd
$ sudo chmod 600 /etc/postfix/sasl_passwd

# finally postmap
$ sudo postmap /etc/postfix/sasl_passwd

# test the postfix
$ sudo postfix check

# restart and test/ send a test mail, check the mail queue
$ sudo systemctl restart postfix
$ echo "Test Postfix Gmail SMTP Relay" | mail -s "Postfix Gmail SMTP Relay" userid@gmail.com
$ mailq
```
* Set up the mailing service to send mail every 5 minutes

```console
$ crontab -e

# add this line
*/5 * * * * /usr/bin/php /project_path/scripts/news_service.php

# all done, now the news_service.php will run every 5 minutes
```
