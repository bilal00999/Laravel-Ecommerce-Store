# 🛍️ Laravel E-Commerce Store

A modern, feature-rich e-commerce platform built with Laravel 11, featuring a beautiful product catalog, shopping cart, user authentication, admin dashboard, and comprehensive order management system.

---

## 📹 Video Demo

**Watch the complete project walkthrough:**

### 👤 User Page

[![User Page Demo](https://img.shields.io/badge/Google%20Drive-User%20Page-4285F4?style=for-the-badge&logo=googledrive)](https://drive.google.com/file/d/12Uk_ZbXHxWp1-NgXpIIeHxp9dYMZcGQZ/view?usp=sharing)

### 👨‍💼 Admin Page

[![Admin Page Demo](https://img.shields.io/badge/Google%20Drive-Admin%20Page-4285F4?style=for-the-badge&logo=googledrive)](https://drive.google.com/file/d/1HSVFH_5_UjE1f5bHb504S4aCTaaZIyqH/view?usp=sharing)

---

## ✨ Features

### 🛒 **Customer Features**

- **Product Catalog** - Browse products with beautiful grid layout, search, and filtering
- **Advanced Filtering** - Filter by category, price range, and sort options
- **Product Details** - Detailed product pages with images, descriptions, and reviews
- **Shopping Cart** - Add/remove items, view cart total, and proceed to checkout
- **Secure Checkout** - Complete order checkout with payment processing
- **User Authentication** - Register, login, and email verification
- **Order Management** - View order history, track orders, and access receipts
- **User Dashboard** - Manage profile, addresses, and saved items
- **Contact System** - Send messages, receive replies, and track communication history

### 👨‍💼 **Admin Features**

- **Admin Dashboard** - Complete overview of store metrics and statistics
- **Product Management** - Create, edit, delete products with image uploads
- **Category Management** - Manage product categories
- **Order Management** - View all orders, update order status, manage fulfillment
- **User Management** - View users, manage permissions, handle admin roles
- **Contact Management** - View and respond to customer messages
- **Image Upload** - Drag-and-drop image upload for products with optimization

### 🎨 **Design & UX**

- **Responsive Design** - Mobile, tablet, and desktop optimization
- **Modern UI** - Beautiful gradient buttons, smooth animations, professional styling
- **Bootstrap 5.3** - Latest Bootstrap framework for consistent design
- **Bootstrap Icons** - Extensive icon library for intuitive navigation
- **Smooth Transitions** - CSS animations and hover effects for better UX
- **Dark/Light Themes Ready** - Infrastructure for theme support

### 🔒 **Security & Authentication**

- **Laravel Sanctum** - Token-based API authentication
- **Password Encryption** - Bcrypt password hashing
- **CSRF Protection** - Built-in CSRF token verification
- **Authorization Gates & Policies** - Fine-grained access control
- **Email Verification** - Verify user emails before account activation
- **Rate Limiting** - API rate limiting for security

### 💾 **Database & Backend**

- **SQLite Database** - Lightweight, file-based database
- **Eloquent ORM** - Powerful object-relational mapping
- **Database Migrations** - Version-controlled database schema
- **Model Relationships** - User, Product, Cart, Order, Category relations
- **Model Factories & Seeders** - Dummy data generation for testing

### 📧 **Communication**

- **Email Notifications** - Order confirmations, email verification
- **Contact Messages** - Customer contact form with admin responses
- **Message Tracking** - Track communication history between users and admins

---

## 🛠️ Tech Stack

| Technology          | Version | Purpose                         |
| ------------------- | ------- | ------------------------------- |
| **Laravel**         | 11.x    | Backend framework               |
| **PHP**             | 8.3+    | Server-side language            |
| **SQLite**          | Latest  | Database                        |
| **Bootstrap**       | 5.3     | CSS framework                   |
| **Vue.js**          | Latest  | JavaScript framework (optional) |
| **Vite**            | Latest  | Build tool & dev server         |
| **Composer**        | Latest  | PHP package manager             |
| **Laravel Sanctum** | Latest  | API authentication              |

---

## 📋 Project Structure

```
ecommerce_store/
├── app/
│   ├── Models/              # Database models (User, Product, Order, etc.)
│   ├── Http/
│   │   ├── Controllers/     # Request handlers
│   │   ├── Middleware/      # HTTP middleware
│   │   └── Requests/        # Form validation
│   ├── Policies/            # Authorization policies
│   ├── Notifications/       # Email notifications
│   └── Listeners/           # Event listeners
├── database/
│   ├── migrations/          # Schema changes
│   ├── seeders/             # Dummy data
│   └── factories/           # Model factories
├── resources/
│   ├── views/
│   │   ├── products/        # Product listing & details
│   │   ├── cart/            # Shopping cart
│   │   ├── checkout/        # Order checkout
│   │   ├── auth/            # Authentication pages
│   │   └── admin/           # Admin dashboard
│   ├── css/                 # Stylesheets
│   └── js/                  # JavaScript files
├── routes/
│   ├── web.php              # Web routes
│   └── api.php              # API routes (optional)
├── config/
│   ├── app.php              # App configuration
│   ├── database.php         # Database configuration
│   ├── mail.php             # Email configuration
│   └── auth.php             # Authentication configuration
├── storage/
│   ├── app/
│   │   └── public/products/ # Uploaded product images
│   ├── framework/           # Cache & sessions
│   └── logs/                # Application logs
└── public/
    └── storage/             # Symlink to storage/app/public

```

---

## 🚀 Quick Start

### Prerequisites

- PHP 8.3 or higher
- Composer
- Node.js & npm (for frontend build tools)
- Laragon or similar local development environment

### Installation Steps

1. **Clone the repository**

    ```bash
    git clone https://github.com/bilal00999/Laravel-Ecommerce-Store.git
    cd ecommerce_store
    ```

2. **Install dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Configure environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Setup database**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

5. **Create storage symlink**

    ```bash
    php artisan storage:link
    ```

6. **Start development server**

    ```bash
    php artisan serve
    ```

7. **Start Vite dev server (in another terminal)**

    ```bash
    npm run dev
    ```

8. **Access the application**
    - Application: http://localhost:8000
    - Admin Dashboard: http://localhost:8000/admin

---

## 📖 Usage Guide

### For Customers

1. Browse products on the homepage
2. Use filters to find products by category or price
3. Click on a product to view details
4. Add items to cart
5. Proceed to checkout to complete purchase
6. View order history in user dashboard

### For Admins

1. Login as admin (see database seeder for credentials)
2. Access admin dashboard at `/admin`
3. Manage products, categories, orders, and users
4. Upload product images with drag-and-drop
5. Respond to customer messages
6. View analytics and statistics

---

## 🔑 Default Credentials (After Seeding)

| Role     | Email             | Password |
| -------- | ----------------- | -------- |
| Admin    | admin@example.com | password |
| Customer | user@example.com  | password |

_Change these credentials after first login!_

---

## 📱 Responsive Breakpoints

- **Desktop**: 1200px+ (Multi-column layout)
- **Tablet**: 768px - 1199px (2-column grid)
- **Mobile**: < 768px (Single column, optimized touch UI)

---

## 🎯 Key Pages & Routes

| Page               | Route               | Description           |
| ------------------ | ------------------- | --------------------- |
| Product Listing    | `/products`         | Browse all products   |
| Product Details    | `/products/{id}`    | View single product   |
| Shopping Cart      | `/cart`             | View cart items       |
| Checkout           | `/checkout`         | Complete purchase     |
| Order Confirmation | `/checkout/success` | Order receipt         |
| User Dashboard     | `/dashboard`        | User profile & orders |
| Contact            | `/contact`          | Send messages         |
| Admin Dashboard    | `/admin`            | Admin panel           |

---

## 🔐 Security Features

- ✅ **CSRF Token Protection** - Prevents cross-site request forgery
- ✅ **Email Verification** - Verify user emails before activation
- ✅ **Authorization Policies** - Fine-grained access control
- ✅ **Password Encryption** - Bcrypt hashing for passwords
- ✅ **Rate Limiting** - Protect against brute force attacks
- ✅ **Secure Headers** - HTTP security headers configured
- ✅ **SQL Injection Prevention** - Eloquent parameterized queries
- ✅ **Input Validation** - Server-side form validation

---

## 📊 Database Schema

### Users Table

- ID, name, email, password, email_verified_at, is_admin

### Products Table

- ID, name, description, price, stock, category_id, image_path, user_id

### Categories Table

- ID, name, slug, description

### Orders Table

- ID, user_id, total_price, status, created_at

### OrderItems Table

- ID, order_id, product_id, quantity, price

### Cart Table

- ID, user_id, created_at

### CartItems Table

- ID, cart_id, product_id, quantity

### ContactMessages Table

- ID, user_id, subject, message, status, created_at

---

## 🎨 Design Highlights

- **Color Scheme**: Purple gradient (#667eea to #764ba2) with gold accents (#FFD700)
- **Typography**: Modern sans-serif with clear hierarchy
- **Spacing**: Consistent 15px border-radius and generous whitespace
- **Animations**: Smooth CSS transitions and hover effects
- **Accessibility**: Semantic HTML, ARIA labels, keyboard navigation

---

## 🐛 Troubleshooting

### Images Not Showing

```bash
php artisan storage:link
php artisan view:clear && php artisan cache:clear
```

### Database Issues

```bash
php artisan migrate:refresh --seed
```

### Compilation Errors

```bash
npm run build
php artisan view:clear
```

### Permission Denied (storage)

```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📝 Recent Updates

- ✨ Complete page layout restructure with modern Flexbox design
- 🎨 Enhanced product card styling with beautiful visuals
- 🔧 Removed duplicate product list template (fixed pagination)
- 📱 Improved responsive design for mobile/tablet/desktop
- 🚀 Optimized performance and caching

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Author

**Bilal Khan**

- GitHub: [@bilal00999](https://github.com/bilal00999)

