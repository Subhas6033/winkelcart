# 🛒 WinkelKart - Multi-Category E-Commerce Platform

WinkelKart is a modern multi-category e-commerce platform built using **Laravel**, designed to provide a seamless shopping experience across various product categories including **Hotels & Resorts, Fashion, Mobile, Electronics, and Appliances**.

---

## 🚀 Features

### 🧑‍💻 User Features

* 🔐 User Authentication (Login/Register)
* 🛍️ Browse products by category
* 🔎 Advanced product search
* 🛒 Add to cart & manage cart
* 📦 Order management
* 👤 User profile & address management

### 🏪 Business Features

* 🏢 Register as a seller (Sell With Us)
* 📦 Manage products
* 📊 Track orders

### 🛠️ Admin Features

* 📊 Admin Dashboard
* 🧾 Manage users & sellers
* 📦 Manage products & categories
* 🎯 Dynamic Hero Slider (category-based)

---

## 🧩 Categories

* 🏨 Hotels & Resorts
* 👗 Fashion (Cosmetics)
* 📱 Mobile
* 💻 Electronics
* 🔌 Appliances

---

## 🛠️ Tech Stack

| Layer    | Technology                   |
| -------- | ---------------------------- |
| Backend  | Laravel (PHP)                |
| Frontend | Blade, HTML, CSS, JavaScript |
| Database | MySQL                        |
| Styling  | Bootstrap 5                  |
| Icons    | Font Awesome, Ionicons       |

---

## ⚙️ Installation

### 1️⃣ Clone the repository

```bash
git clone https://github.com/your-username/WinkelKart.git
cd WinkelKart
```

---

### 2️⃣ Install dependencies

```bash
composer install
npm install
```

---

### 3️⃣ Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

---

### 4️⃣ Configure database

Update `.env`:

```env
DB_DATABASE=winkelkart
DB_USERNAME=root
DB_PASSWORD=
```

---

### 5️⃣ Run migrations

```bash
php artisan migrate
```

---

### 6️⃣ Start server

```bash
php artisan serve
```

---


## 🔐 Security Notes

* ⚠️ Always set in production:

```env
APP_ENV=production
APP_DEBUG=false
```

---

## 🚫 Git Best Practices

Make sure `.gitignore` includes:

```
/vendor
/node_modules
.env
*.zip
```

---

## 📸 Screenshots (Optional)

*Add screenshots of your homepage, dashboard, and product pages here.*

---

## 🤝 Contributing

Contributions are welcome!

1. Fork the repo
2. Create a new branch
3. Commit changes
4. Push and create a PR

---

## 📄 License

This project is licensed under the **MIT License**.

---

## 👨‍💻 Author

**Pratik Guha** SRD Technologies
📧 [info@winkelkart.com](mailto:info@winkelkart.com)
🌐 WinkelKart

---

## ⭐ Support

If you like this project, please ⭐ the repository!

---
