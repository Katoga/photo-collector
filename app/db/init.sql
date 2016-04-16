CREATE TABLE "events" (
  "event_id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text COLLATE 'BINARY' NOT NULL,
  "url" text COLLATE 'BINARY' NOT NULL
);

CREATE UNIQUE INDEX "events_name" ON "events" ("name");
CREATE UNIQUE INDEX "events_url" ON "events" ("url");
