# StayEase — PHP + MySQL backend

## What's included
- `database/schema.sql` — tables: users, hotels, bookings, newsletter_subscribers, favorites (+ sample hotel rows matching your HTML)
- `config/database.php` — PDO connection, fill in your DB credentials
- `includes/bootstrap.php` — shared session/JSON/CORS setup, included by every endpoint
- `api/register.php`, `api/login.php`, `api/logout.php`, `api/check_session.php` — account + session
- `api/hotels.php` — GET, supports `?category=luxury&location=Dubai`
- `api/book.php` — POST, creates a booking
- `api/my_bookings.php` — GET, a signed-in user's bookings
- `api/subscribe.php` — POST, newsletter form
- `frontend-integration.js` — example fetch calls to wire your existing `script.js` buttons to these endpoints

## 1. Requirements
- PHP 8.0+ with the `pdo_mysql` extension
- MySQL 8 (or MariaDB 10.4+)
- Any host that gives you both: shared hosting (Hostinger, GoDaddy, Namecheap), a VPS (DigitalOcean, Linode, AWS Lightsail), or a PaaS (Render, Railway)

## 2. Create the database
```bash
mysql -u root -p -e "CREATE DATABASE stayease CHARACTER SET utf8mb4;"
mysql -u root -p -e "CREATE USER 'stayease_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD';"
mysql -u root -p -e "GRANT ALL PRIVILEGES ON stayease.* TO 'stayease_user'@'localhost'; FLUSH PRIVILEGES;"
mysql -u stayease_user -p stayease < database/schema.sql
```
On shared hosting, do the equivalent in cPanel's "MySQL Databases" + phpMyAdmin (create DB, create user, attach user to DB, then import `schema.sql` via phpMyAdmin's Import tab).

## 3. Configure credentials
Edit `config/database.php` and set `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` to match step 2. Keep this file outside your public webroot if your host allows it, or protect it via `.htaccess`.

## 4. Upload the files
Folder layout on the server should look like:
```
public_html/
├── index.html          (your existing HTML file)
├── style.css
├── script.js            (merge in frontend-integration.js)
├── api/
│   ├── register.php
│   ├── login.php
│   ├── logout.php
│   ├── check_session.php
│   ├── hotels.php
│   ├── book.php
│   ├── my_bookings.php
│   └── subscribe.php
├── config/database.php
└── includes/bootstrap.php
```
Upload via FTP/SFTP (FileZilla), your host's file manager, or `scp`/`rsync` if you're on a VPS.

## 5. Update the frontend
Open `frontend-integration.js`, copy its functions into `script.js`, replacing the placeholder `bookHotel`, `loginMessage`, `subscribe`, and `searchHotels` functions already in the HTML. Add a call to `loadHotels()` on page load if you want the hotel grid to come from the database instead of the static cards.

## 6. Test the API directly
```bash
curl https://yourdomain.com/api/hotels.php
curl -X POST https://yourdomain.com/api/subscribe.php \
  -H "Content-Type: application/json" \
  -d '{"email":"you@example.com"}'
```
You should get back `{"success":true, ...}` JSON.

## 7. Go live checklist
- Enable HTTPS (Let's Encrypt via your host, or Cloudflare) — required for cookies/sessions to work reliably
- In `includes/bootstrap.php`, change `Access-Control-Allow-Origin: *` to your exact domain once frontend and API share a domain (recommended) — same-origin means you can usually drop the CORS headers entirely
- Set `session.cookie_secure = 1` in `php.ini` (or via `ini_set` in bootstrap.php) once you're on HTTPS
- Turn off PHP error display in production (`display_errors = Off` in php.ini); log errors to a file instead
- Back up the database on a schedule (most hosts offer one-click MySQL backups)

## Extending later
- Add a `favorites` toggle endpoint (table is already in the schema) for the heart icon
- Add an admin-only endpoint to add/edit hotels instead of editing the DB by hand
- Add email confirmation for bookings (PHPMailer + SMTP)
