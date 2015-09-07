CREATE SEQUENCE job_seq;
CREATE TABLE IF NOT EXISTS "jobs" (
  "id" integer PRIMARY KEY default nextval('job_seq'),
  "handler" text NOT NULL,
  "queue" varchar(255) NOT NULL DEFAULT 'default',
  "attempts" integer NOT NULL DEFAULT 0,
  "run_at" timestamp null,
  "locked_at" timestamp null,
  "locked_by" VARCHAR(255) NULL,
  "failed_at" timestamp null,
  "error" TEXT NULL,
  "created_at" timestamp null,
  CHECK (attempts>=0)
);
