#!/bin/bash

dbuser="poll_admin"
dbpass="almalinuxapachemariadbphp"

echo; echo "Five most recent entries:"; echo
mariadb -u "$dbuser" -p"$dbpass" -e \
  "SELECT date, question \
  FROM questions \
  ORDER BY date \
  DESC LIMIT 5;" \
  poll_site

echo; echo "Date to publish new question: "; read -r inputdate
echo; echo "Question: "; read -r inputquestion
echo; echo "Answer A: "; read -r inputanswera
echo; echo "Answer B: "; read -r inputanswerb; echo

mariadb -u "$dbuser" -p"$dbpass" -e \
  "INSERT INTO questions (date, question, answer_a, answer_b) \
  VALUES ('$inputdate', '$inputquestion', '$inputanswera', '$inputanswerb');" \
  poll_site

mariadb -u "$dbuser" -p"$dbpass" -e \
  "SELECT date, question, answer_a, answer_b \
  FROM questions \
  ORDER BY date \
  DESC LIMIT 1;" \
  poll_site
