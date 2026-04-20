# UniStay

A Laravel-based student accommodation management system for the University of Botswana. It supports student registration with document uploads, welfare officer application review, landlord property management, and on/off-campus accommodation browsing.

---

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18 & npm
- MySQL or MariaDB
- Git

---

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/Cyberbiso/ub-accommodation-information-system.git
cd ub-accommodation-information-system
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install frontend dependencies

```bash
npm install
```

### 4. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ub_accommodation
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Link storage (for document uploads)

```bash
php artisan storage:link
```

### 7. Build frontend assets

```bash
npm run build
```

Or for development with hot reload:

```bash
npm run dev
```

### 8. Start the development server

```bash
php artisan serve
```

The app will be available at **http://localhost:8000**

---

## User Roles

| Role | Access |
|------|--------|
| **Student** | Register, upload documents, apply for accommodation, browse properties |
| **Welfare Officer** | Review applications, verify documents, manage on-campus rooms |
| **Landlord** | List and manage off-campus properties, handle viewing requests |
| **Admin** | User management, property approval |

---

## Key Features

- Student registration with document upload (acceptance letter, proof of registration, passport for international students)
- Document verification workflow for welfare officers
- On-campus accommodation applications
- Off-campus property browsing and viewing requests
- Role-based dashboards and access control

---

## License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
