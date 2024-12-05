# 💄🎀 Cosmetics Fashion Sales Management System 🎀💄

## 📜 Overview
The **💄 Cosmetics Fashion Sales Management System** is a robust web 🌐 application designed to manage the sales of cosmetics and fashion products 👗. This platform provides comprehensive functionalities for customers 👥 to explore a wide range of products 🛍️, add items to a shopping cart 🛒, and make secure payments 💳 through an easy-to-use checkout process. It also offers administrators 👨‍💼 full control over managing products 🛍️, users 👥, and orders 📦 through an admin dashboard, enabling efficient business operations.

This project utilizes **HTML, CSS, JavaScript, PHP, and MySQL** to create a reliable system. Additionally, it integrates **Bootstrap** to ensure a responsive design 📱, enhancing the user experience across various devices, and **Stripe** as a payment gateway 💸 to provide secure payment capabilities.

## 📁 Project Folder Structure

### **1. 📂 project_root/**
- **📁 assets/**: Contains all static files like CSS, JavaScript, and images 🖼️. These assets are used for styling 🎨, adding interactivity 💡, and displaying content.
  - `css/`: Includes stylesheets that define the visual presentation of the application, ensuring an appealing user interface 🌟.
  - `js/`: Contains JavaScript files that provide dynamic features ⚡ and interactivity 💡, such as form validation and cart operations.
  - `images/`: Stores product and UI images 🖼️, including product photos 📸 and icons used throughout the application.
- **🗂️ config/**: Stores configuration files 🛠️, including settings needed to connect to the database 🏦.
  - `database.php`: Contains the database connection setup, providing access to MySQL 🛢️.
- **📂 includes/**: Common reusable components 🔄 that appear on multiple pages 📄.
  - `header.php`, `footer.php`, `navbar.php`: These files contain shared components like the website's header, footer, and navigation bar 🧭 for consistency.
- **🗃️ models/**: Handles all database interactions for different entities 🛢️, encapsulating the database operations.
  - `user.php`, `product.php`, `order.php`: Scripts for managing users 👤, products 🛍️, and orders 📦, including CRUD (Create, Read, Update, Delete) operations 🔄.
- **🌐 views/**: HTML views for different pages 📄, serving as the presentation layer 🖥️ of the application.
  - `home.php`, `product_list.php`, `cart.php`, `checkout.php`: Customer-facing pages 👥 that facilitate product browsing, cart management, and checkout 🛒.
  - `login.php`, `register.php`: Authentication pages 🔑 for users to log in or register.
- **📋 controllers/**: Business logic scripts 📜 that process user inputs ✍️ and manage data flow 🔄 between models and views.
  - `login_controller.php`, `register_controller.php`, `cart_controller.php`, etc.: Each controller manages specific aspects of user interaction.
- **🖥️ admin/**: Contains admin pages for managing the system 👨‍💼, providing administrators with tools 🛠️ to control products 🛍️, users 👥, and orders 📦.
  - `dashboard.php`, `manage_products.php`, `manage_orders.php`, `manage_users.php`: Functionalities for admins to manage the platform ⚙️.
- **📦 vendor/**: Contains third-party libraries and dependencies 📦 used in the project.
  - `bootstrap/`: Bootstrap library for styling 🎨, providing pre-designed components for a responsive layout 📱.
  - `payment_gateway/stripe/`: Stripe SDK for payment integration 💸, allowing secure payment transactions 💳.
- **🖼️ uploads/**: Stores uploaded product images 📸, enabling admins to add product photos dynamically.
- **🏠 index.php**: The main entry point for the website 🌐, providing an introduction to the platform.
- **📜 README.md**: Project documentation 📚, explaining the structure 🏗️, usage 📋, and other important aspects of the project.

## 🛠️ Prerequisites
- **PHP** (version 7.4 or above) 💻: Server-side scripting language used for backend development.
- **MySQL** (version 5.7 or above) 🛢️: Relational database management system used to store data related to products 🛍️, users 👥, and orders 📦.
- **Apache or Nginx Web Server** 🌐: Web server software to serve the application 🖥️.
- **Composer** (for dependency management) 📦: Tool for managing PHP dependencies, including third-party libraries.

## 🔧 Installation
1. **Clone the repository** 💾:
   ```bash
   git clone https://github.com/your-repo-url.git
   ```
2. **Navigate to the project directory** 📂:
   ```bash
   cd cosmetics-fashion-sales-management
   ```
3. **Install dependencies** (if using Composer) 📦:
   ```bash
   composer install
   ```
4. **Database Setup** 🛢️:
   - Create a MySQL database named `cosmetics_fashion_db` 🏦.
   - Import the SQL script (`database.sql`) to create the necessary tables 📋, ensuring the correct structure for storing products, users, and orders.
   - Update the database connection settings in `config/database.php` to match your database credentials 🔑.

5. **Configure Stripe Payment Gateway** 💳:
   - Navigate to `vendor/payment_gateway/stripe/init.php` 💻.
   - Set your **Stripe Secret Key** in `init.php` to enable payment transactions 💸:
     ```php
     \Stripe\Stripe::setApiKey('YOUR_STRIPE_SECRET_KEY');
     ```
6. **Run the Application** 🏃‍♂️:
   - If using a local server (such as XAMPP or MAMP), place the project in the `htdocs` folder 📂 and access it via `http://localhost/cosmetics-fashion-sales-management` 🌐. This will allow you to view the homepage 🏠 and begin exploring functionalities.

## 💡 Usage
### **Frontend (Customer) 👥**
- **🏠 Home Page**: Displays featured products 🛍️, highlighting new arrivals 🌟 and best sellers 🥇 to attract customer interest.
- **🛍️ Product Listing**: Allows customers to view all products, filter by category 🔍, and view detailed information 📝, including product descriptions, pricing 💲, and availability.
- **🛒 Cart Management**: Customers can add products to their cart 🛒, adjust quantities ➕➖, and remove items as needed, ensuring a seamless shopping experience.
- **📤 Checkout**: Secure payment integration with Stripe 💳 ensures that payment details are processed safely 🔒 and transactions are completed smoothly.

### **Backend (Admin) 👨‍💼**
- **📊 Admin Dashboard**: Provides an overview of system metrics 📈 (e.g., total users 👥, orders 📦, products 🛍️), giving administrators insights into business performance 📉.
- **🛍️ Manage Products**: Admins can add ➕, edit ✍️, and delete ❌ products, update stock levels 📦, and upload product images 📸 to maintain an up-to-date catalog.
- **📦 Manage Orders**: Admins can view all orders and update their statuses 🔄, such as marking orders as shipped 🚚, delivered 📬, or canceled ❌.
- **👥 Manage Users**: Admins can view and manage user details 🛠️, including editing user information ✍️ or assigning specific roles 🧑‍🏫.

## 💻 Technologies Used
- **Frontend**: HTML, CSS, JavaScript, Bootstrap 💡 to create a responsive and interactive user interface 📱.
- **Backend**: PHP, MySQL 🛢️ for server-side processing 💻 and data storage.
- **Payment Integration**: Stripe SDK 💸 for handling payments securely 🔒 and efficiently.
- **CSS Framework**: Bootstrap for responsive design 📱, ensuring compatibility across different screen sizes and devices 📊.

## 🔒 Security Considerations
- **🛡️ Password Hashing**: All user passwords are hashed using `password_hash()` 🛡️, making it difficult for attackers to obtain raw passwords 🔐 even if the database is compromised.
- **🛠️ Prepared Statements**: SQL queries are executed using prepared statements to prevent SQL injection 🔓, a common vulnerability in web applications.
- **🔒 HTTPS**: Ensure that the application is deployed over HTTPS 🌐 to protect sensitive customer information such as login credentials 🔑 and payment details 💳.

## 💡 Future Enhancements
- **📝 Product Reviews**: Allow customers to leave reviews for products 🛍️, helping other customers make informed decisions based on real experiences 🗣️.
- **📦 Order Tracking**: Provide customers with order tracking capabilities 🚚, allowing them to see the status of their orders from processing to delivery 📬.
- **🎁 Discount Codes**: Integrate a discount code system 💸 to offer promotions and discounts, improving customer engagement 📊 and sales.
- **🏛️ Role-Based Access Control**: Implement different roles 🎭 such as admin, vendor, and support staff, giving each role specific permissions 🔑 to enhance security 🔒 and efficiency in system management.
- **🌐 Multi-Language Support**: Expand the platform to support multiple languages 🗣️, catering to a broader audience 🌍 and improving user experience for non-English speakers.
- **📊 Advanced Analytics**: Develop an analytics dashboard 📈 for administrators to gain insights into customer behavior 👥, popular products 🛍️, and sales trends 📉, helping in data-driven decision-making.

## 🔧 Troubleshooting
- **🛢️ Database Connection Issues**: Ensure that the database credentials in `config/database.php` are correct ✅, including the database name 🏦, username, and password 🔑.
- **🛠️ Permission Issues**: Check folder permissions for `uploads/` 📂 to allow image uploads 🖼️, ensuring that the server can write files to the directory.
- **💸 Stripe Errors**: Verify that the Stripe API key is correctly set 🔑 and that the account is in live mode 🟢 if deploying. Test payments in the sandbox environment 🏖️ before moving to production.
- **📦 Missing Dependencies**: If you encounter missing class errors 🚫, make sure all Composer dependencies are installed by running `composer install` 🛠️.

## 📜 License
This project is open-source 🔓 and available under the [MIT License](LICENSE), which allows you to freely use, modify ✍️, and distribute the code 📝.

## 🚀 Contributing
Feel free to open issues 📝 or create pull requests 🔄 if you want to contribute to this project 🛠️. Contributions are welcome 🎉! Whether it is a bug fix 🐞, a new feature ✨, or an improvement to the documentation 📄, your help is greatly appreciated 🙌.

## 👥 Contact
For any questions or issues ❓, please contact [your-email@example.com] ✉️. We look forward to hearing from you 👂 and are happy to assist with any inquiries related to the project 🤝.

