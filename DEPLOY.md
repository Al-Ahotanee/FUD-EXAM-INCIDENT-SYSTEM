# Deploying FUD OIRMF to Render + Neon (free tier)

This app was originally built for MySQL + GitHub Codespaces. It's now ported to
**PostgreSQL** (all schema + queries rewritten) so it can run on **Neon's free
Postgres** and be hosted on **Render's free web service** plan. All features, the
UI, and every API action are unchanged — only the database layer and hosting
config changed.

## What changed under the hood
- `api.php` no longer opens its own MySQL connection or creates the schema inline.
  It now just does `require 'db.php'; $pdo = get_pdo();`
- `db.php` (new) connects to Postgres via `DATABASE_URL` and creates all 12 tables,
  the `updated_at` triggers, and seeds the same 5 demo accounts — idempotently, on
  first request, exactly like before.
- MySQL-only SQL was converted: `AUTO_INCREMENT`→`SERIAL`, `ENUM`→`VARCHAR + CHECK`,
  `TINYINT(1)`→`BOOLEAN`, `DATETIME`→`TIMESTAMP`, `NOW()+INTERVAL`, `EXTRACT()`
  instead of `MONTH()/YEAR()`, `TO_CHAR()` instead of `DATE_FORMAT()`, and
  `RETURNING id` instead of `lastInsertId()`.
- `index.php`, `admin.php`, `eo.php`, `users.php` — **untouched**. They're static
  SPA frontends that only ever talked to `api.php` over `fetch`/`axios`, so they
  needed zero changes.

## 1. Create the Neon database
1. Sign up at neon.tech, create a project (free tier).
2. Open the project dashboard → **Connection string** → copy the pooled connection
   string (it looks like `postgresql://user:pass@ep-xxxx.aws.neon.tech/dbname?sslmode=require`).
3. That's it — you don't need to run any SQL yourself. `db.php` creates and seeds
   every table automatically the first time the app is hit.

## 2. Deploy to Render
**Option A — render.yaml (recommended)**
1. Push this project to a GitHub repo.
2. In Render: New → Blueprint → pick the repo. Render reads `render.yaml`
   automatically and provisions a Docker web service on the free plan.
3. In the service's Environment tab, set `DATABASE_URL` to the Neon connection
   string from step 1.
4. Deploy. Render builds the `Dockerfile` (PHP 8.2 + Apache + `pdo_pgsql`) and
   starts the app, listening on the `$PORT` Render assigns.

**Option B — manual web service**
1. New → Web Service → connect the repo → Environment: **Docker**.
2. Plan: Free.
3. Add environment variable `DATABASE_URL` = your Neon connection string.
4. Create Web Service.

## 3. First login
Once deployed, visit the Render URL. Go to **Staff Portal** and log in with any
of the seed accounts (unchanged from the original README):

| Role | Email | Password |
|---|---|---|
| Admin | admin@fud.edu.ng | Admin@1234 |
| Exam Officer | officer@fud.edu.ng | Officer@1234 |
| Invigilator | invigilator@fud.edu.ng | Invigi@1234 |
| HOD/Dean | hod@fud.edu.ng | Hod@12345 |
| Committee | committee@fud.edu.ng | Commit@1234 |

**Change these passwords (or disable/replace the accounts) before using this with
real data** — they're public in this repo's history.

## Known limitations on the free tier (read before a real submission/demo)
- **Ephemeral filesystem**: Render's free plan doesn't persist disk across
  restarts/redeploys, so files in `uploads/` (incident evidence) will be lost
  when the container restarts. For a class project demo this is usually fine;
  for anything longer-lived, swap the upload handling in `api.php`
  (`report_incident` action) for a persistent store (Cloudinary, S3-compatible
  bucket, etc.) — happy to help wire that up if you need it.
- **Cold starts**: free Render services spin down after ~15 min idle and take a
  few seconds to wake back up on the next request.
- **Sessions**: still PHP's default file-based sessions. Fine for a single free
  instance; if you ever scale to multiple instances, sessions won't be shared
  unless moved to the DB or Redis.
- **Neon free tier** auto-suspends the compute after inactivity too — the first
  query after a pause takes a bit longer while it wakes up, then it's normal
  speed.

## Local development
```bash
docker build -t fud-oirmf .
docker run -p 8080:10000 -e PORT=10000 -e DATABASE_URL="postgresql://user:pass@localhost:5432/fud_ims_spa?sslmode=disable" fud-oirmf
```
Or point `DATABASE_URL` at your Neon project directly and skip local Postgres entirely.
