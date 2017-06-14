Team planner
============

This project requires:
 
 1. PHP 7.x.
 2. MySQL server present.
 3. Some form of serving PHP pages and static files (PHP's built-in server, apache, nginx, ...)
 
Setup
-----
 
 1. The web server must be set up to serve pages from `./web_root` and autorun `index.php`.
    1. Alternatively, your URLs will look silly since your must prepend `web_root/`, and it'd also expose the entire codebase.
 2. Import `team_planner.sql` into an empty MySQL schema.
 3. Modify `./app/config.php` with proper connection information for the database.
 
Optionally, set up your webserver to rewrite all non-existing URLs to `index.php?route=(path)` and modify `URL_BASE` and `URL_PARAM_START` in `./app/config.php` accordingly.

If you wish to upload profile pictures for people, ./web_root/uploads must be writable by the webserver.