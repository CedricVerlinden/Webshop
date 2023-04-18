# Webshop

This is a custom-made webshop designed to meet the needs of both customers and store owners. This README file will provide you with an overview of the features included in the webshop, as well as instructions on how to set it up and run it on your local machine.

## Features

- Product catalog with category filtering
- Shopping cart with the ability to add, edit, and remove products
- Checkout process with the ability to enter shipping and payment information
- User authentication and authorization with login and registration functionality
- Admin panel with the ability to manage products, categories, orders, and customers

## Requirements

- PHP
- MySQL
- Apache or Nginx (XAMPP for Windows)

## Getting Started

1. Clone the repository:

`git clone https://github.com/cedricverlinden/webshop.git`

2. Import the SQL dump file `database.sql` into your MySQL database.

3. Configure the database connection by editing the file `connection.inc.php` and setting the following values:

`$connection = new mysqli("address", "username", "password", "database");`

4. Start your web server and navigate to the `public_html` directory in your web browser

5. Enjoy!

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
