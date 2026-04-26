# UB-UniStay

A Laravel-based student accommodation management system for the University of Botswana. Supports student registration with document uploads, welfare officer review workflows, landlord property management, on/off-campus accommodation browsing, and integrated Paddle payments.

---

## Requirements

| Tool | Version |
|------|---------|
| PHP | >= 8.2 |
| Composer | Latest |
| Node.js + npm | >= 18 |
| MySQL / MariaDB | >= 8 |
| Git | Any |
| ngrok | Latest (free tier works) |

---

## Local Setup

### 1. Clone the repository

```bash
git clone https://github.com/Cyberbiso/ub-accommodation-information-system.git
cd ub-accommodation-information-system
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ub_accommodation
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run migrations & seed

```bash
php artisan migrate
php artisan db:seed        # optional — seeds demo users and data
```

### 5. Link storage

```bash
php artisan storage:link
```

### 6. Build frontend assets

```bash
npm run build
```

> For hot-reload during development run `npm run dev` in a separate terminal instead.

### 7. Start the server

**Using XAMPP (recommended on Windows):**
- Place the project in `C:\xampp\htdocs\`
- Start Apache and MySQL from the XAMPP control panel
- App available at `http://localhost/ub-accommodation-information-system/public`

**Using Laravel's built-in server:**
```bash
php artisan serve
```
App available at `http://localhost:8000`

---

## Setting Up ngrok (required for Paddle payments)

Paddle requires a publicly accessible HTTPS URL for its checkout overlay and webhooks. ngrok creates a secure tunnel from your local machine to the internet.

### 1. Install ngrok

Download from [ngrok.com/download](https://ngrok.com/download) and add it to your PATH.

### 2. Create a free ngrok account and authenticate

```bash
ngrok config add-authtoken YOUR_NGROK_TOKEN
```

Get your token from the ngrok dashboard after signing up.

### 3. Start the tunnel

```bash
ngrok http 80
```

> If using `php artisan serve` on port 8000, run `ngrok http 8000` instead.

ngrok will output a forwarding URL like:
```
Forwarding  https://famine-collage-cane.ngrok-free.dev -> http://localhost:80
```

Copy that `https://` URL — you will need it for Paddle setup below.

### 4. Update APP_URL

In `.env`, set:
```env
APP_URL=https://your-ngrok-url.ngrok-free.dev
```

Then clear the config cache:
```bash
php artisan optimize:clear
```

> **Note:** Free ngrok URLs change every time you restart ngrok. Update `APP_URL` and the Paddle webhook URL whenever this happens.

---

## Setting Up Paddle Payments (Sandbox)

Paddle is used for processing accommodation payments. Follow these steps to configure it.

### 1. Create a Paddle sandbox account

Go to [sandbox-vendors.paddle.com](https://sandbox-vendors.paddle.com) and sign up.

### 2. Get your API credentials

In the Paddle sandbox dashboard:

| Key | Location |
|-----|----------|
| `PADDLE_API_KEY` | Developer Tools → Authentication → API keys |
| `PADDLE_CLIENT_SIDE_TOKEN` | Developer Tools → Authentication → Client-side tokens (**must start with `test_`**) |
| `PADDLE_WEBHOOK_SECRET` | Developer Tools → Notifications → your endpoint → secret key |

### 3. Create a product and price

1. Go to **Catalog → Products** → create a product (e.g. "Accommodation Payment")
2. Copy the `pro_` ID → set as `PADDLE_PRODUCT_ID`
3. Go to **Catalog → Prices** → create a price for that product with **no fixed amount** (custom/variable pricing)
4. Copy the `pri_` ID → set as `PADDLE_PRICE_ID`

### 4. Approve your ngrok domain

1. Go to **Checkout → Website Approval**
2. Add your ngrok domain (e.g. `famine-collage-cane.ngrok-free.dev`) — without `https://`
3. Wait for it to show **Approved**

### 5. Set the default payment link domain

1. Go to **Checkout → Checkout Settings**
2. Under **Default payment link**, set it to your full ngrok URL: `https://your-ngrok-url.ngrok-free.dev`

### 6. Register the webhook endpoint

1. Go to **Developer Tools → Notifications**
2. Create a new notification with URL:
   ```
   https://your-ngrok-url.ngrok-free.dev/ub-accommodation-information-system/public/paddle/webhook
   ```
3. Subscribe to the `transaction.completed` event
4. Copy the **secret key** → set as `PADDLE_WEBHOOK_SECRET`

### 7. Update your `.env`

```env
PADDLE_ENVIRONMENT=sandbox
PADDLE_API_KEY=pdl_sdbx_apikey_...
PADDLE_CLIENT_SIDE_TOKEN=test_...
PADDLE_WEBHOOK_SECRET=pdl_ntfset_...
PADDLE_PRODUCT_ID=pro_...
PADDLE_PRICE_ID=pri_...
```

Then run:
```bash
php artisan optimize:clear
```

### 8. Test a payment

Use Paddle's test card in the checkout overlay:
- **Card:** `4242 4242 4242 4242`
- **Expiry:** any future date
- **CVV:** any 3 digits

---

## User Roles

| Role | Access |
|------|--------|
| **Student** | Register, upload documents, apply for accommodation, browse properties, make payments |
| **Welfare Officer** | Review applications, verify documents, manage on-campus rooms |
| **Landlord** | List and manage off-campus properties, handle viewing requests |
| **Admin** | User management, property approval |

---

## Key Features

- Student registration with document upload (acceptance letter, proof of registration, passport for international students)
- Document verification workflow for welfare officers
- On-campus accommodation applications
- Off-campus property browsing, viewing requests, and Paddle payment integration
- Role-based dashboards and access control
- Real-time notifications

---

## Troubleshooting

**Paddle overlay shows "Something went wrong"**
- Check browser console for `[Paddle] Initialized. Environment:` — must say `sandbox`, not `production`
- Verify `PADDLE_CLIENT_SIDE_TOKEN` starts with `test_`
- Run `php artisan optimize:clear` after any `.env` change

**Payment not marking as completed**
- The frontend calls `/payments/paddle/verify` after checkout — check `storage/logs/laravel.log` for errors
- Ensure `PADDLE_API_KEY` is the sandbox key (starts with `pdl_sdbx_`)

**ngrok URL changed**
1. Update `APP_URL` in `.env`
2. Update the webhook URL in Paddle dashboard → Developer Tools → Notifications
3. Update the approved domain if it changed → Checkout → Website Approval
4. Run `php artisan optimize:clear`

---

## License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
