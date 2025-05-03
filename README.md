# Grades Website

A web application built with PHP and MySQL, designed to run locally using XAMPP.

## Prerequisites

- XAMPP installed on your system.
- Basic knowledge of PHP, MySQL, and phpMyAdmin.

## Setup Instructions

1. **Install and Start XAMPP**
   - Download and install XAMPP from the official website.
   - Start the Apache and MySQL modules in the XAMPP Control Panel.
   - Download and extract the project files.
   - Move all files to `C:\xampp\htdocs\registration`. Do not rename any files.

2. **Create the Database**
   - Open [http://localhost/phpmyadmin/index.php](http://localhost/phpmyadmin/index.php) in your browser.
   - Create a new database named `registration_db`.
   - Import the SQL queries from the `registration_db.sql` file to create the required tables.

3. **Test the Website**
   - Navigate to [http://localhost/registration/](http://localhost/registration/).
   - Verify that the website loads and the database connection works.

## Troubleshooting

- Ensure Apache and MySQL are running in XAMPP.
- Check that the database name (`registration_db`) and table structures match the SQL file.
- Confirm the file path (`C:\xampp\htdocs\registration`) if the website does not load.
