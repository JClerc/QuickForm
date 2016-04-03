-- Adminer 4.2.1 SQLite 3 dump

DROP TABLE IF EXISTS "answers";
CREATE TABLE answers (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  fill_id INTEGER NOT NULL,
  question_id INTEGER NOT NULL,
  content TEXT NOT NULL,
  choice_id INTEGER NOT NULL
);

INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (1,	1,	1,	'',	1);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (2,	1,	5,	'',	6);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (3,	1,	5,	'',	8);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (4,	1,	3,	'Steak frites',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (5,	2,	1,	'',	1);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (6,	2,	5,	'',	7);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (7,	2,	5,	'',	8);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (8,	2,	3,	'Pizza',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (9,	2,	4,	'France, Canada, Japon, Australie',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (10,	3,	1,	'',	2);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (11,	3,	5,	'',	6);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (12,	3,	5,	'',	9);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (13,	3,	3,	'Salade',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (14,	3,	4,	'Espagne et Italie',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (15,	4,	1,	'',	1);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (16,	4,	5,	'',	6);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (17,	4,	5,	'',	8);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (18,	4,	3,	'Sushis',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (19,	5,	1,	'',	2);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (20,	5,	5,	'',	9);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (21,	6,	1,	'',	1);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (22,	6,	5,	'',	6);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (23,	6,	5,	'',	7);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (24,	6,	3,	'Gratin daufinois',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (25,	6,	4,	'Finlande et Danemark',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (26,	7,	1,	'',	1);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (27,	7,	5,	'',	7);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (28,	7,	3,	'Tagliatelle au poulet',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (29,	7,	4,	'USA, Mexique et Qatar',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (30,	8,	1,	'',	2);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (31,	8,	5,	'',	6);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (32,	8,	5,	'',	8);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (33,	8,	3,	'Poisson pané',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (34,	8,	4,	'Aucun',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (35,	9,	1,	'',	2);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (36,	9,	5,	'',	6);
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (37,	9,	3,	'McDonalds',	'0');
INSERT INTO "answers" ("id", "fill_id", "question_id", "content", "choice_id") VALUES (38,	9,	4,	'Colombie, Afrique du Sud et Chine',	'0');

DROP TABLE IF EXISTS "choices";
CREATE TABLE choices (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  question_id INTEGER NOT NULL,
  content TEXT NOT NULL
);

INSERT INTO "choices" ("id", "question_id", "content") VALUES (1,	1,	'Un homme');
INSERT INTO "choices" ("id", "question_id", "content") VALUES (2,	1,	'Une femme');
INSERT INTO "choices" ("id", "question_id", "content") VALUES (6,	5,	'Faire du sport');
INSERT INTO "choices" ("id", "question_id", "content") VALUES (7,	5,	'Regarder la TV');
INSERT INTO "choices" ("id", "question_id", "content") VALUES (8,	5,	'Surfer sur internet');
INSERT INTO "choices" ("id", "question_id", "content") VALUES (9,	5,	'Lire un livre');

DROP TABLE IF EXISTS "fills";
CREATE TABLE fills (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  form_id INTEGER NOT NULL
);

INSERT INTO "fills" ("id", "form_id") VALUES (1,	1);
INSERT INTO "fills" ("id", "form_id") VALUES (2,	1);
INSERT INTO "fills" ("id", "form_id") VALUES (3,	1);
INSERT INTO "fills" ("id", "form_id") VALUES (4,	1);
INSERT INTO "fills" ("id", "form_id") VALUES (5,	1);
INSERT INTO "fills" ("id", "form_id") VALUES (6,	1);
INSERT INTO "fills" ("id", "form_id") VALUES (7,	1);
INSERT INTO "fills" ("id", "form_id") VALUES (8,	1);
INSERT INTO "fills" ("id", "form_id") VALUES (9,	1);

DROP TABLE IF EXISTS "forms";
CREATE TABLE forms (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title TEXT NOT NULL,
  desc TEXT NOT NULL,
  email TEXT NOT NULL,
  updated INTEGER NOT NULL
);

INSERT INTO "forms" ("id", "title", "desc", "email", "updated") VALUES (1,	'Test de personnalité',	'Quel genre de personne es tu ?',	'qform.service@gmail.com',	1459622256);

DROP TABLE IF EXISTS "questions";
CREATE TABLE questions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  form_id INTEGER NOT NULL,
  type TEXT NOT NULL,
  mandatory INTEGER NOT NULL,
  phrase TEXT NOT NULL,
  position INTEGER NOT NULL
);

INSERT INTO "questions" ("id", "form_id", "type", "mandatory", "phrase", "position") VALUES (1,	1,	'radio',	1,	'Vous êtes..',	'0');
INSERT INTO "questions" ("id", "form_id", "type", "mandatory", "phrase", "position") VALUES (3,	1,	'input',	'0',	'Quel est votre repas préféré ?',	2);
INSERT INTO "questions" ("id", "form_id", "type", "mandatory", "phrase", "position") VALUES (4,	1,	'textarea',	'0',	'Quels pays avez vous visités ?',	3);
INSERT INTO "questions" ("id", "form_id", "type", "mandatory", "phrase", "position") VALUES (5,	1,	'checkbox',	1,	'Vous aimez..',	1);

DROP TABLE IF EXISTS "sqlite_sequence";
CREATE TABLE sqlite_sequence(name,seq);

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('answers',	'38');
INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('choices',	'13');
INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('fills',	'9');
INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('forms',	'2');
INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('questions',	'9');

-- 
