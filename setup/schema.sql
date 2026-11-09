CREATE TABLE questions (
  id INTEGER PRIMARY KEY,
  date TEXT UNIQUE NOT NULL,
  question TEXT NOT NULL,
  answer_a TEXT NOT NULL,
  answer_b TEXT NOT NULL,
  results_a INTEGER NOT NULL DEFAULT 0 check (results_a >= 0),
  results_b INTEGER NOT NULL DEFAULT 0 check (results_b >= 0)
);