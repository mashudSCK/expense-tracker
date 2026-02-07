# EXPENSE TRACKER SYSTEM DOCUMENTATION
## Complete Academic Project - CodeIgniter 4

---

## TABLE OF CONTENTS
1. [System Overview](#system-overview)
2. [System Architecture](#system-architecture)
3. [Database Design](#database-design)
4. [Module Documentation](#module-documentation)
5. [API Integration](#api-integration)
6. [Security Implementation](#security-implementation)
7. [User Guide](#user-guide)
8. [Technical Specifications](#technical-specifications)

---

## 1. SYSTEM OVERVIEW

### 1.1 Purpose
The Expense Tracker is a web-based application that enables users to record, manage, and track their daily expenses across multiple currencies with automatic conversion to Philippine Peso (PHP).

### 1.2 Objectives
- Provide secure user authentication
- Enable expense management with CRUD operations
- Support multi-currency expense recording
- Automatically convert foreign currencies to PHP
- Provide role-based access control
- Generate expense summaries and reports

### 1.3 Target Users
- **Regular Users**: Individuals tracking personal expenses
- **Administrators**: System managers with full access

### 1.4 Key Features
- Secure login/logout
- Multi-currency support (PHP, USD, EUR)
- Automatic currency conversion via API
- Expense categorization
- Dashboard with expense summaries
- Admin category management
- Responsive Bootstrap UI

---

## 2. SYSTEM ARCHITECTURE

### 2.1 Architecture Pattern: MVC (Model-View-Controller)

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │
       ▼
┌─────────────────────────────────┐
│      VIEWS (Presentation)       │
│  - Login Page                   │
│  - Dashboard                    │
│  - Expense Forms                │
│  - Category Management          │
└──────────┬──────────────────────┘
           │
           ▼
┌─────────────────────────────────┐
│    CONTROLLERS (Logic)          │
│  - AuthController               │
│  - DashboardController          │
│  - ExpenseController            │
│  - CategoryController           │
└──────────┬──────────────────────┘
           │
           ▼
┌─────────────────────────────────┐
│     MODELS (Data Access)        │
│  - UserModel                    │
│  - ExpenseModel                 │
│  - CategoryModel                │
└──────────┬──────────────────────┘
           │
           ▼
┌─────────────────────────────────┐
│    DATABASE (MySQL)             │
│  - users                        │
│  - expenses                     │
│  - categories                   │
└─────────────────────────────────┘
```

### 2.2 Technology Stack

| Component | Technology |
|-----------|------------|
| Framework | CodeIgniter 4.4+ |
| Language | PHP 7.4+ |
| Database | MySQL 5.7+ |
| Frontend | HTML5, Bootstrap 5 |
| Server | Apache (XAMPP) |
| API | ExchangeRate-API |

### 2.3 Directory Structure

```
expense-tracker/
├── app/
│   ├── Config/
│   │   ├── Routes.php           # Route definitions
│   │   └── Filters.php          # Filter configurations
│   ├── Controllers/             # Business logic
│   ├── Models/                  # Data access layer
│   ├── Views/                   # Presentation layer
│   ├── Filters/                 # Authentication filter
│   ├── Libraries/               # Custom libraries
│   └── Database/Migrations/     # Database migrations
├── public/
│   └── index.php               # Entry point
├── writable/                   # Logs, cache, sessions
├── .env                        # Environment configuration
└── README.md                   # Documentation
```

---

## 3. DATABASE DESIGN

### 3.1 Entity Relationship Diagram (ERD)

```
┌──────────────┐          ┌─────────────────┐          ┌──────────────┐
│    users     │          │    expenses     │          │  categories  │
├──────────────┤          ├─────────────────┤          ├──────────────┤
│ id (PK)      │─────────<│ user_id (FK)    │>─────────│ id (PK)      │
│ username     │          │ category_id(FK) │          │ name         │
│ password     │          │ description     │          │ created_at   │
│ role         │          │ amount          │          │ updated_at   │
│ created_at   │          │ currency        │          └──────────────┘
│ updated_at   │          │ converted_amount│
└──────────────┘          │ expense_date    │
                          │ created_at      │
                          │ updated_at      │
                          └─────────────────┘
```

### 3.2 Table Specifications

#### users
```sql
CREATE TABLE users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);
```

#### categories
```sql
CREATE TABLE categories (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);
```

#### expenses
```sql
CREATE TABLE expenses (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    category_id INT(11) UNSIGNED NOT NULL,
    description TEXT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) NOT NULL,
    converted_amount DECIMAL(10,2) NOT NULL,
    expense_date DATE NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
```

### 3.3 Sample Data

#### Users
| id | username | password | role |
|----|----------|----------|------|
| 1 | admin | (hashed) | admin |
| 2 | user1 | (hashed) | user |

#### Categories
| id | name |
|----|------|
| 1 | Food & Dining |
| 2 | Transportation |
| 3 | Shopping |
| 4 | Entertainment |
| 5 | Bills & Utilities |
| 6 | Healthcare |
| 7 | Education |
| 8 | Others |

---

## 4. MODULE DOCUMENTATION

### 4.1 Authentication Module

#### AuthController

**Purpose**: Handles user authentication

**Methods**:
- `login()`: Display login form
- `authenticate()`: Process login credentials
- `logout()`: Destroy session and logout

**Flow**:
```
User → Login Form → Validate Credentials → Create Session → Redirect to Dashboard
```

**Security**:
- Password verification using `password_verify()`
- CSRF token validation
- Session-based authentication

### 4.2 Dashboard Module

#### DashboardController

**Purpose**: Display expense summaries

**Methods**:
- `index()`: Show dashboard with statistics

**Features**:
- Total expenses (in PHP)
- Expenses by category
- Recent 10 expenses
- Role-based data filtering

**Data Display**:
- Admin: All users' expenses
- User: Own expenses only

### 4.3 Expense Module

#### ExpenseController

**Purpose**: Manage expense CRUD operations

**Methods**:
- `index()`: List all expenses
- `create()`: Show add expense form
- `store()`: Save new expense
- `edit($id)`: Show edit form
- `update($id)`: Update expense
- `delete($id)`: Delete expense

**Currency Conversion Flow**:
```
User Input (USD 100) → CurrencyService → API Call → Get Rate
→ Convert (100 * 56) → Store PHP 5,600
```

**Validation Rules**:
- category_id: required, integer
- description: required, min 3 chars
- amount: required, decimal, > 0
- currency: required, in_list[PHP,USD,EUR]
- expense_date: required, valid_date

**Authorization**:
- Users can only edit/delete own expenses
- Admin can manage all expenses

### 4.4 Category Module

#### CategoryController

**Purpose**: Manage expense categories (Admin only)

**Methods**:
- `index()`: List categories
- `create()`: Show add form
- `store()`: Save category
- `edit($id)`: Show edit form
- `update($id)`: Update category
- `delete($id)`: Delete category

**Access Control**:
- Only admin role can access
- Redirect non-admin users

---

## 5. API INTEGRATION

### 5.1 Currency Service

**Class**: `App\Libraries\CurrencyService`

**Purpose**: Handle currency conversion via external API

#### Key Methods

```php
convert($amount, $fromCurrency, $toCurrency)
// Converts amount from one currency to another

getExchangeRate($from, $to)
// Retrieves exchange rate

fetchRates($baseCurrency)
// Fetches rates from API with caching
```

### 5.2 API Details

**Provider**: ExchangeRate-API  
**Endpoint**: `https://api.exchangerate-api.com/v4/latest/{currency}`  
**Method**: GET  
**Response Format**: JSON

**Sample Response**:
```json
{
  "base": "USD",
  "date": "2024-12-15",
  "rates": {
    "PHP": 56.25,
    "EUR": 0.92,
    "USD": 1.00
  }
}
```

### 5.3 Caching Mechanism

- **Cache File**: `writable/cache/exchange_rates.json`
- **Cache Duration**: 1 hour (3600 seconds)
- **Purpose**: Reduce API calls, improve performance

**Cache Structure**:
```json
{
  "USD": {
    "rates": { "PHP": 56.25, "EUR": 0.92 },
    "timestamp": 1702627200
  }
}
```

### 5.4 Fallback Rates

If API fails, system uses these rates:
- 1 USD = 56.00 PHP
- 1 EUR = 61.00 PHP
- 1 PHP = 1 PHP

### 5.5 Error Handling

```php
try {
    $rate = $currencyService->convert(100, 'USD', 'PHP');
} catch (Exception $e) {
    // Fallback to 1:1 rate
    log_message('error', $e->getMessage());
}
```

---

## 6. SECURITY IMPLEMENTATION

### 6.1 Password Security

**Hashing Algorithm**: bcrypt (via `password_hash()`)

```php
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$isValid = password_verify($inputPassword, $hashedPassword);
```

### 6.2 CSRF Protection

**Implementation**: Session-based tokens

```php
// In forms
<?= csrf_field() ?>

// Automatic validation by CI4
```

### 6.3 Session Security

**Configuration** (.env):
```ini
app.sessionDriver = FileHandler
app.sessionMatchIP = false
app.sessionTimeToUpdate = 300
```

**Session Data**:
```php
[
    'user_id' => 1,
    'username' => 'admin',
    'role' => 'admin',
    'logged_in' => true
]
```

### 6.4 Authentication Filter

**Class**: `App\Filters\AuthFilter`

**Purpose**: Protect routes from unauthorized access

```php
public function before(RequestInterface $request, $arguments = null)
{
    if (!session()->get('logged_in')) {
        return redirect()->to('/login');
    }
}
```

### 6.5 Input Validation

**Server-Side Validation Rules**:
- Required fields
- Data type validation
- Length constraints
- Whitelist validation (e.g., currency codes)

### 6.6 SQL Injection Prevention

**Method**: Query Builder with prepared statements

```php
$this->where('user_id', $userId)->findAll();
// Automatically escapes and sanitizes
```

### 6.7 Authorization Checks

```php
// Check ownership
if ($expense['user_id'] != session()->get('user_id') 
    && session()->get('role') !== 'admin') {
    return redirect()->with('error', 'Unauthorized');
}
```

---

## 7. USER GUIDE

### 7.1 For Regular Users

#### Login
1. Go to `http://localhost/expense-tracker/public/`
2. Enter username: `user1`
3. Enter password: `user123`
4. Click "Login"

#### View Dashboard
- See total expenses in PHP
- View breakdown by category
- See recent 10 expenses

#### Add Expense
1. Click "Expenses" in sidebar
2. Click "Add New Expense"
3. Fill in:
   - Expense Date
   - Category (dropdown)
   - Description
   - Amount
   - Currency (PHP/USD/EUR)
4. Click "Save Expense"
5. System automatically converts to PHP

#### Edit Expense
1. Go to "Expenses"
2. Click edit icon on expense row
3. Modify fields
4. Click "Update Expense"

#### Delete Expense
1. Go to "Expenses"
2. Click delete icon
3. Confirm deletion

### 7.2 For Administrators

#### All User Features PLUS:

#### Manage Categories
1. Click "Categories" in sidebar
2. View all categories

#### Add Category
1. Click "Add New Category"
2. Enter category name
3. Click "Save Category"

#### Edit Category
1. Click edit icon
2. Modify name
3. Click "Update Category"

#### Delete Category
1. Click delete icon
2. Confirm deletion
3. **Note**: Deletes all expenses in that category

#### View All Expenses
- Dashboard shows expenses from all users
- Expenses page shows all users' data

---

## 8. TECHNICAL SPECIFICATIONS

### 8.1 System Requirements

**Server**:
- Apache 2.4+
- PHP 7.4+ with extensions:
  - mysqli
  - intl
  - json
  - mbstring
  - curl
- MySQL 5.7+ or MariaDB 10.3+

**Client**:
- Modern web browser
- JavaScript enabled
- Internet connection (for API)

### 8.2 Performance Considerations

**Database Indexing**:
- Primary keys on all tables
- Foreign key indexes
- Index on user_id in expenses

**Caching**:
- Exchange rate caching (1 hour)
- Session file caching

**Query Optimization**:
- Use of JOIN for related data
- Limited result sets (e.g., recent 10)

### 8.3 Scalability

**Current Design**:
- Single server deployment
- File-based sessions
- Local database

**Future Enhancements**:
- Redis for session storage
- Database replication
- Load balancing
- CDN for assets

### 8.4 Browser Compatibility

- Chrome 90+
- Firefox 88+
- Edge 90+
- Safari 14+

### 8.5 Code Standards

**PSR Compliance**:
- PSR-1: Basic coding standard
- PSR-4: Autoloading standard
- PSR-12: Extended coding style

**Naming Conventions**:
- Classes: PascalCase
- Methods: camelCase
- Variables: camelCase
- Constants: UPPER_CASE

### 8.6 Testing Recommendations

**Unit Testing**:
- Test Models (CRUD operations)
- Test Currency conversion logic
- Test validation rules

**Integration Testing**:
- Test authentication flow
- Test expense creation with currency conversion
- Test role-based access

**User Acceptance Testing**:
- Login/logout
- Add/edit/delete expenses
- Currency conversion accuracy
- Category management

---

## APPENDIX A: API Response Examples

### Successful Currency Conversion
```json
{
  "base": "USD",
  "rates": {
    "PHP": 56.25,
    "EUR": 0.92
  }
}
```

### API Error Response
```json
{
  "error": "Invalid currency code"
}
```

---

## APPENDIX B: Database Queries

### Get User Expenses with Categories
```sql
SELECT e.*, c.name as category_name
FROM expenses e
JOIN categories c ON c.id = e.category_id
WHERE e.user_id = 1
ORDER BY e.expense_date DESC;
```

### Get Total Expenses by Category
```sql
SELECT c.name, SUM(e.converted_amount) as total
FROM expenses e
JOIN categories c ON c.id = e.category_id
WHERE e.user_id = 1
GROUP BY e.category_id
ORDER BY total DESC;
```

---

## APPENDIX C: Troubleshooting Guide

### Problem: Cannot login
**Solutions**:
1. Clear browser cookies
2. Check database connection
3. Verify user exists in database
4. Check password hash

### Problem: Currency conversion fails
**Solutions**:
1. Check internet connection
2. Verify API endpoint is accessible
3. System will use fallback rates automatically
4. Check logs in `writable/logs/`

### Problem: 404 errors
**Solutions**:
1. Enable mod_rewrite in Apache
2. Check .htaccess in public folder
3. Verify base_url in .env

---

**END OF DOCUMENTATION**

*Last Updated: December 2024*  
*Version: 1.0*  
*Status: Production Ready*
