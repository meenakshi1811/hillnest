# Hillnest — Pure Bilona Ghee from Upper Shimla

E-commerce platform for **Hillnest**, a Himalayan brand selling pure bilona cow ghee (with apple orchard products planned for the future).

Built with **Laravel 12**, **Tailwind CSS 4**, and **Vite**.

## Features

- Modern storefront (home, shop, product pages, about)
- Session-based shopping cart & checkout (Cash on Delivery)
- User registration & login — track orders in account
- Admin portal: dashboard, orders, customers, products, earnings reports

## Quick Start (XAMPP)

1. **Database** — Create a MySQL database (e.g. `hillnest_db`) in phpMyAdmin.

2. **Environment** — In `.env` set:
   ```
   DB_DATABASE=hillnest_db
   DB_USERNAME=root
   DB_PASSWORD=
   APP_URL=http://localhost/hillnest/public
   ```

3. **Install & setup** (from project folder):
   ```bash
   composer dump-autoload
   php artisan migrate --seed
   npm install
   npm run build
   ```

4. **Logo** — Place your logo in either:
   - `public/images/logo.png` (recommended), or
   - `images/logo.png` at project root (auto-copied to public)

5. **Open in browser:**
   - Store: `http://localhost/hillnest/public`
   - Admin: login with credentials below, then visit `/admin`

## Default Admin Login

| Email | Password |
|-------|----------|
| `admin@hillnest.in` | `admin123` |

**Change this password immediately in production.**

## Customer Flow

1. Browse ghee products → Add to cart
2. Checkout (guest or logged-in)
3. Register/login to view **My Orders**

## Admin URLs

| Section | URL |
|---------|-----|
| Dashboard | `/admin` |
| Orders | `/admin/orders` |
| Customers | `/admin/users` |
| Products | `/admin/products` |
| Reports | `/admin/reports` |

## Products Seeded

- Pure Bilona Cow Ghee — 250g, 500g, 1kg, 2kg

Edit prices, stock, and descriptions from the admin products section.

## Development

```bash
php artisan serve
npm run dev
```

---

© Hillnest — Upper Shimla, Himachal Pradesh
