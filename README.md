# 💰 Expense Tracker System

A comprehensive web-based expense management application built with CodeIgniter 4, featuring multi-currency support with automatic conversion to Philippine Peso (PHP).

## 📋 Overview

The Expense Tracker System enables users to efficiently record, manage, and track their daily expenses across multiple currencies. The system provides secure authentication, role-based access control, and real-time currency conversion through external API integration.

## ✨ Key Features

- **🔐 Secure Authentication**: User login/logout with password hashing
- **💵 Multi-Currency Support**: Track expenses in PHP, USD, and EUR
- **🔄 Automatic Conversion**: Real-time currency conversion via ExchangeRate-API
- **📊 Dashboard Analytics**: Expense summaries and statistics
- **🏷️ Category Management**: Organize expenses by customizable categories
- **👥 Role-Based Access**: Admin and regular user roles
- **📱 Responsive Design**: Bootstrap-powered UI for all devices
- **💾 Offline Fallback**: Cached exchange rates for offline operation

## 🛠️ Technology Stack

- **Backend**: CodeIgniter 4 (PHP 8.1+)
- **Database**: MySQL 5.7+
- **Frontend**: Bootstrap 5, HTML5, CSS3, JavaScript
- **API Integration**: ExchangeRate-API
- **Server**: Apache (XAMPP)

## 📁 Project Structure

```
expense-tracker/
├── app/
│   ├── Controllers/     # Application controllers
│   ├── Models/          # Database models
│   ├── Views/           # User interface views
│   ├── Libraries/       # Custom libraries (CurrencyService)
│   ├── Filters/         # Authentication filters
│   └── Database/        # Migrations and seeds
├── public/              # Public web directory
├── writable/            # Cache, logs, sessions, uploads
├── vendor/              # Composer dependencies
├── database_schema.sql  # Database schema file
├── SETUP_GUIDE.md      # Detailed setup instructions
└── SYSTEM_DOCUMENTATION.md  # Complete system documentation
```

## 🚀 Quick Start

### Prerequisites

- **XAMPP** (Apache & MySQL)
- **Composer** (PHP package manager)
- **PHP 8.1 or higher**
- Internet connection (for API and dependencies)

### Installation

1. **Clone or extract the project** to `c:\xampp\htdocs\expense-tracker`

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   copy env .env
   ```
   Edit `.env` and configure:
   - Database settings
   - Base URL: `http://localhost:8080`

4. **Create database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create database: `expense_tracker`
   - Import: `database_schema.sql`

5. **Start the application**
   ```bash
   php spark serve
   ```

6. **Access the system**
   - Open browser: `http://localhost:8080`

For detailed installation instructions, see [SETUP_GUIDE.md](SETUP_GUIDE.md)

## 👤 Default Users

### Admin Account
- **Username**: `admin`
- **Password**: `admin123`
- **Access**: Full system access including category management

### Regular User Account
- **Username**: `user1`
- **Password**: `pass123`
- **Access**: Personal expense management

## 📚 Documentation

- **[SETUP_GUIDE.md](SETUP_GUIDE.md)** - Detailed step-by-step installation guide
- **[SYSTEM_DOCUMENTATION.md](SYSTEM_DOCUMENTATION.md)** - Complete technical documentation
- **[PROJECT_DELIVERY.md](PROJECT_DELIVERY.md)** - Project deliverables and completion status

## 🔧 System Requirements

### Server Requirements
- PHP version 8.1 or higher
- MySQL 5.7 or higher
- Apache web server

### Required PHP Extensions
- `intl` - Internationalization support
- `mbstring` - Multibyte string support
- `json` - JSON support (enabled by default)
- `mysqlnd` - MySQL native driver
- `libcurl` - HTTP requests for API integration

## 📊 Database Schema

The system uses three main tables:

- **users** - User accounts and authentication
- **categories** - Expense categories
- **expenses** - Expense records with currency data

See `database_schema.sql` for complete schema details.

## 🔌 API Integration

The system integrates with [ExchangeRate-API](https://www.exchangerate-api.com/) for real-time currency conversion:
- Base currency: PHP (Philippine Peso)
- Supported currencies: PHP, USD, EUR
- Caching: 1-hour cache duration
- Fallback rates for offline operation

## 🛡️ Security Features

- Password hashing with PHP's `password_hash()`
- Session-based authentication
- CSRF protection (CodeIgniter 4 default)
- SQL injection prevention via Query Builder
- XSS filtering on outputs
- Auth filter for protected routes

## 🎯 Usage

1. **Login** with your credentials
2. **Dashboard** - View expense summary and statistics
3. **Add Expense** - Record new expenses in any supported currency
4. **Manage Expenses** - Edit or delete existing expenses
5. **Categories** (Admin only) - Add/edit expense categories
6. **Logout** - Securely end your session

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

This is an academic project. For issues or suggestions, please refer to the system documentation.

## 📞 Support

For technical questions or system documentation:
- Review [SYSTEM_DOCUMENTATION.md](SYSTEM_DOCUMENTATION.md)
- Check [SETUP_GUIDE.md](SETUP_GUIDE.md) for installation issues

---

**Built with CodeIgniter 4** - [Official Documentation](https://codeigniter.com/user_guide/)
