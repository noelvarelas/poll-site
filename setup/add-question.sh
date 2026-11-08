#!/bin/bash

if [[ $EUID -ne 0 ]]; then
  echo "This script must be run as root"
  exit 1
fi

echo; echo "Five most recent entries:"
mariadb -u root -e \
  "SELECT date, question \
  FROM questions \
  ORDER BY date \
  DESC LIMIT 5;" \
  poll_site

echo; echo "Date to publish new question in YYYY-MM-DD format: "; read -r inputdate
echo; echo "Question: "; read -r inputquestion
echo; echo "Answer A: "; read -r inputanswera
echo; echo "Answer B: "; read -r inputanswerb

inputquestion=$(echo "$inputquestion" | sed "s/'/''/g")
inputanswera=$(echo "$inputanswera" | sed "s/'/''/g")
inputanswerb=$(echo "$inputanswerb" | sed "s/'/''/g")

mariadb -u root -e \
  "INSERT INTO questions (date, question, answer_a, answer_b) \
  VALUES ('$inputdate', '$inputquestion', '$inputanswera', '$inputanswerb');" \
  poll_site

echo; echo "Result:"
mariadb -u root -e \
  "SELECT date, question, answer_a, answer_b \
  FROM questions \
  ORDER BY date \
  DESC LIMIT 1;" \
  poll_site
