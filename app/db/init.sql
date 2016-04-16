/* events */
CREATE TABLE "events" (
  "event_id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text COLLATE 'BINARY' NOT NULL,
  "url" text COLLATE 'BINARY' NOT NULL
);

CREATE UNIQUE INDEX "events_name" ON "events" ("name");
CREATE UNIQUE INDEX "events_url" ON "events" ("url");


/* users */
CREATE TABLE "users" (
  "user_id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text COLLATE 'BINARY' NOT NULL,
  "login" text COLLATE 'BINARY' NOT NULL,
  "password" text COLLATE 'BINARY' NOT NULL
);

CREATE UNIQUE INDEX "users_name" ON "users" ("name");
CREATE UNIQUE INDEX "users_login" ON "users" ("login");

