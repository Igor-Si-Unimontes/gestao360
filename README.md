# System PDV - TCC
This TCC project focuses on developing a point of sale (PDV) system tailored for rural areas, aiming to streamline sales, </br>
inventory, and administrative processes in small businesses.

## Features
<ul>
  <li>
    PHP >= 8.4
  </li>
  <li>
    Composer
  </li>
  <li>
    Node and Npm
  </li>
  <li>
    Docker
  </li>
</ul>

## Setup

### 1. Install Dependencies  
Run the following command in the project root to install PHP and JavaScript dependencies:  
```bash
composer install
```

```bash
npm install
```

### 2. Configure Environment
Create a .env file based on the contents of .env.example: 
```bash
cp .env.example .env
```

Make sure to configure your environment variables as needed. Don't forget to put a value for APP_KEY:
```bash
php artisan key:generate
```

### 3. Start the Database
The project uses Mysql as the database, managed via Docker for convenience. To start the database, run:
```bash
docker compose up -d
```

```bash
DB_CONNECTION=mysql
DB_HOST=mysql_db
DB_PORT=3306
DB_DATABASE=gestao360
DB_USERNAME=user
DB_PASSWORD=secret
```

⚠️ Feel free to use a different database if preferred, but ensure your .env file is configured accordingly.

### 4. Run Migrations 
Apply the database migrations:
```bash
docker exec -it laravel_app php artisan migrate
```

If the database does not exist, only select 'yes' to create it.

### 5. Run Seeders
Apply the seeders:
```bash
docker exec -it laravel_app php artisan db:seed --class=RoleSeeder
```
AND
```bash
docker exec -it laravel_app php artisan db:seed --class=UserSeeder
```

### 5. Start Apllication
To run the website, we need to enable docker for the services to work.
```bash
docker compose up -d
```
### 6. Credentials
To log into the system, we need credentials.
```bash
Email: admin@gmail.com
Password: admin123@ 
```

## How to View Emails?
For convenience, all generated emails will be logged in the Laravel log file located at storage/logs/laravel.log. You can check this file to see the email content generated during the application process.

However, if you want to see it working in practice and send actual emails, you can configure an email service that best suits your needs. Laravel supports various email services, such as SMTP, Resend, Mailgun, SendGrid, etc. You can configure the .env file to set up your preferred email service.

Once configured, emails will be sent through the service, and you can see them in your inbox.

## About the Error Messages

The input field error messages are currently set in Brazilian Portuguese, but you can easily switch them to any language.

If you prefer English messages, simply remove the second array from the validate method. By default, Laravel will use English messages when no language array is provided.


## 🚀 Coming Soon

