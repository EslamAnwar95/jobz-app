# Jobz App - Laravel 11 Job Board API

A modern RESTful API for job board management built with Laravel 11.  
Supports two distinct user roles: **Companies** and **Candidates**.

---

## 🚀 Features

- 🔐 Separate authentication for Companies & Candidates (Laravel Passport)
- 🧾 Job posting, updating, and soft-deleting by companies
- 🌍 Public job listings with filters & pagination
- 📎 Candidates can apply with resume and cover letter
- ⚙️ Queued file processing (resume/cover letter) via Laravel Queues
- ✅ Modular API structure, request validation, and resources
- 📬 Postman Collection included

---

## 📦 Tech Stack

- Laravel 11
- Laravel Passport (OAuth2)
- Laravel Queues (database driver)
- MySQL
- Postman (for API testing)

---

## 🛠️ Installation

```bash
git clone https://github.com/EslamAnwar95/jobz-app.git
cd jobz-app
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan queue:work
