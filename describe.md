# Cosmetics Fashion E-commerce Website

## Project Overview
This is a PHP-based e-commerce website for cosmetics and fashion products. The project follows an MVC (Model-View-Controller) architecture and includes both user-facing features and an administrative dashboard.

## Technology Stack
- **Backend**: PHP 7+
- **Database**: MySQL (via PDO)
- **Frontend**: HTML5, CSS3, JavaScript
- **CSS Framework**: Bootstrap 5.3.2
- **Icons**: Font Awesome 6.4.0
- **Server**: Apache (XAMPP)

## Project Structure
```
Web_ASM/
├── admin/                 # Administrative dashboard files
├── assets/               # Static assets (CSS, JS, images)
├── config/               # Configuration files
├── controllers/          # MVC Controllers
├── core/                 # Core framework files
├── database/            # Database-related files
├── includes/            # Reusable components
├── models/              # MVC Models
├── views/               # MVC Views
├── uploads/             # File upload directory
├── vendor/              # Third-party dependencies
└── logs/                # Application logs
```

## Key Features

### User Features
1. **Authentication**
   - User registration
   - User login/logout
   - Password recovery
   - Session management

2. **Product Management**
   - Product browsing
   - Product search
   - Product categories
   - Product details view

3. **Shopping Features**
   - Shopping cart
   - Order management
   - User profile management

### Admin Features
1. **Dashboard**
   - Overview statistics
   - Recent orders
   - User activities

2. **Product Management**
   - Add/Edit/Delete products
   - Category management
   - Inventory management

3. **User Management**
   - User role management (Admin/Regular users)
   - User account management

## Database Structure

### Main Tables
1. **users**
   - user_id (Primary Key)
   - username
   - email
   - password_hash
   - role_id
   - created_at

2. **products**
   - Product details
   - Category relationships
   - Pricing information

3. **categories**
   - Category hierarchy
   - Category metadata

4. **orders**
   - Order tracking
   - Order status
   - Customer information

## Security Features
1. **Authentication Security**
   - Password hashing using PHP's password_hash()
   - Session security measures
   - CSRF protection
   - XSS prevention

2. **Access Control**
   - Role-based access control
   - Admin area protection
   - Session timeout management

## Configuration
- Database configuration in `config/constants.php`
- Base URL configuration for deployment
- Error reporting settings
- Session security settings

## Default Credentials
- **Admin Account**
  - Username: admin
  - Password: admin123
  - Role: Administrator (role_id: 2)

## Installation Steps
1. Install XAMPP (or similar PHP development environment)
2. Clone repository to `htdocs` directory
3. Import database schema using `cosmetics_fashion_db.sql`
4. Configure database connection in `config/constants.php`
5. Run `test_db.php` to verify database setup
6. Access the website through localhost

## Development Guidelines
1. **Code Organization**
   - Follow MVC pattern
   - Use prepared statements for database queries
   - Maintain separation of concerns

2. **Security Practices**
   - Input validation
   - Output sanitization
   - Secure session handling

3. **Error Handling**
   - Comprehensive error logging
   - User-friendly error messages
   - Debug mode configuration

## File Descriptions

### Core Files
- `index.php`: Main entry point
- `login.php`: User authentication
- `register.php`: New user registration
- `test_db.php`: Database connection testing

### Configuration Files
- `constants.php`: Global constants and configuration
- `.htaccess`: Apache configuration
- `database.php`: Database connection handling

### Important Directories
- `controllers/`: Business logic
- `models/`: Data models
- `views/`: User interface templates
- `includes/`: Shared components

## Maintenance
1. **Regular Tasks**
   - Log rotation
   - Database backup
   - Security updates

2. **Performance Optimization**
   - Cache management
   - Database optimization
   - Asset optimization

## Future Enhancements
1. **Planned Features**
   - Enhanced search functionality
   - User reviews and ratings
   - Payment gateway integration
   - Multi-language support

2. **Technical Improvements**
   - API development
   - Mobile responsiveness
   - Performance optimization
   - Enhanced security measures

## Support and Documentation
- Project documentation in `README.md`
- Code comments for functionality
- Database schema documentation
- Security guidelines and best practices
