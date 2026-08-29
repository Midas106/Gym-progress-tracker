IRON LOG — setup on XAMPP
==========================

1. Copy the whole "gym-tracker" folder into your XAMPP htdocs directory, e.g.:
     Windows:  C:\xampp\htdocs\gym-tracker
     Mac:      /Applications/XAMPP/htdocs/gym-tracker

2. Open the XAMPP Control Panel and start Apache and MySQL.

3. Create the database:
     - Go to http://localhost/phpmyadmin
     - Click "Import" in the top menu
     - Choose the file sql/schema.sql from this folder
     - Click "Go"
   (This creates a "gym_tracker" database with the tables and a default weekly split.)

4. Open the app in your browser:
     http://localhost/gym-tracker/index.html

That's it — everything you enter is now saved in real MySQL tables on your laptop, not
in the browser. You can close the tab, restart your laptop, or switch browsers and your
data will still be there as long as XAMPP's MySQL is running.

Notes
-----
- Default XAMPP MySQL login is user "root" with no password. If you've set a MySQL
  root password, open api/db.php and update the $pass value.
- If you ever see a red "Couldn't reach the database" banner in the app, it means
  Apache/MySQL isn't running, or step 3 hasn't been done yet.
- Deleting an exercise from your split does NOT delete its logged history — it just
  stops showing it on the Split page. Its past sessions still count on the Progress page.
- If you already had this app set up before and just updated the files: re-import
  sql/schema.sql once more (phpMyAdmin > Import) to add the new "attendance" table.
  It won't touch your existing data — it only adds the missing table.
- Calendar days turn green two ways: automatically when you log a set on that date,
  or manually by clicking a day and using the "Mark: went to the gym" button inside.
- Click any past or today day on the calendar to open it and edit its weight/sets/reps
  for any exercise — you're not limited to editing today's numbers anymore.
