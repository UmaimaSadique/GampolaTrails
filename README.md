# Gampola Trails

One-day trip planner for places within 25 km of Gampola, Sri Lanka.
Stack: HTML, CSS, JavaScript, PHP, MySQL (XAMPP).

## Run it (XAMPP)

1. Copy this folder to `C:\xampp\htdocs\gampola-trails`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open phpMyAdmin (`http://localhost/phpmyadmin`) -> **Import** -> choose `database.sql` -> Go.
4. Visit `http://localhost/gampola-trails/`.

DB settings are in `config.php` (XAMPP defaults: user `root`, empty passaword).

**Admin:** `http://localhost/gampola-trails/admin/login.php` - user `admin`, password `admin123`


## Notes

- Coordinates, opening hours and fees in `database.sql` are approximate
- Maps need an internet connection (OpenStreetMap tiles). The plan is stored in the browser (localStorage).
- Travel times are estimates (straight-line distance x 1.4 / 30 km/h)


