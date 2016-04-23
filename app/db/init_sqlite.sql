/* events */
CREATE TABLE "events" (
  "event_id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text COLLATE 'BINARY' NOT NULL,
  "url" text COLLATE 'BINARY' NOT NULL
);

CREATE UNIQUE INDEX "events_name" ON "events" ("name");
CREATE UNIQUE INDEX "events_url" ON "events" ("url");


/* authors */
CREATE TABLE "authors" (
  "author_id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text COLLATE 'BINARY' NOT NULL,
  "login" text COLLATE 'BINARY' NOT NULL,
  "password" text COLLATE 'BINARY' NOT NULL,
  "roles" text
);

CREATE UNIQUE INDEX "authors_name" ON "authors" ("name");
CREATE UNIQUE INDEX "authors_login" ON "authors" ("login");

INSERT INTO "authors" (
	"name",
	"login",
	"password",
	"roles"
)
VALUES (
	"Admin",
	"admin",
	"$2y$10$oX.pGHsiu2ZHf38.oOXzJek5SATIRcRu7dwnHi6MgKRVNONnKUsoa", -- "adminace"
	"admin"
);
