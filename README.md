# Expense Tracker API

A Laravel 10+ API-based Expense Tracker with Docker and Laravel Sail.

## 🚀 Quick Setup

### 1. Clone the Repository

```bash
git clone https://github.com/Basharkhan/expense-tracker.git
cd expense-tracker
```

Start docker with laravel sail
cp .env.example .env
./vendor/bin/sail up -d

Install dependency and generate key
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate

Access laravel app
http://localhost:8080

Environment variables
APP_PORT=8080
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
FORWARD_DB_PORT=3307
DB_DATABASE=expense_tracker
DB_USERNAME=sail  
DB_PASSWORD=password
