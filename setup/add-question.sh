#!/bin/sh

db_path="/var/www/poll-site/private/database.sqlite"

echo; echo "Five most recent entries:"; echo
sqlite3 -header -column "$db_path" \
  "SELECT date, question \
  FROM questions \
  ORDER BY date \
  DESC LIMIT 5;"

echo; echo "Date to publish new question in YYYY-MM-DD format: "; read -r inputdate
if ! echo "$inputdate" | grep -Eq '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'; then
  echo "Error: date must be in YYYY-MM-DD format"
  exit 1
fi
echo; echo "Question: "; read -r inputquestion
echo; echo "Answer A: "; read -r inputanswera
echo; echo "Answer B: "; read -r inputanswerb

inputquestion=$(echo "$inputquestion" | sed "s/'/''/g")
inputanswera=$(echo "$inputanswera" | sed "s/'/''/g")
inputanswerb=$(echo "$inputanswerb" | sed "s/'/''/g")

sqlite3 "$db_path" \
  "INSERT INTO questions (date, question, answer_a, answer_b) \
  VALUES ('$inputdate', '$inputquestion', '$inputanswera', '$inputanswerb');"

echo; echo "Result:"; echo
sqlite3 -header -column "$db_path" \
  "SELECT date, question, answer_a, answer_b \
  FROM questions \
  WHERE date = '$inputdate';"
echo