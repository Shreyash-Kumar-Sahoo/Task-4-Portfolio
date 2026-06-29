# Software Requirement Specification (SRS)
**Project Name:** Apex Store (Premium E-Commerce Platform)
**Project Type:** Full-Stack Web Application

## 1. Project Overview
Apex Store is a premium, high-end e-commerce web application focused on selling luxury streetwear, footwear, and accessories. The platform is designed to provide a seamless, minimalist shopping experience with robust backend management.

## 2. User Roles
The system supports two distinct user roles, each with specific permissions:

1.  **Customer (Standard User)**
    *   Any registered user who visits the storefront to browse or purchase products.
    *   *Permissions:* Can browse products, view product details, search/filter inventory, manage a shopping cart, place orders, and view personal order history.
2.  **Administrator (Admin)**
    *   Staff members responsible for managing the store operations.
    *   *Permissions:* Full access to the Admin Dashboard. Can Create, Read, Update, and Delete (CRUD) products, manage inventory sizing, view all customer orders, update order fulfillment status, and view platform analytics.

## 3. Core Features
*   **Secure Authentication:** Registration and Login functionality utilizing `password_hash` encryption, along with a secure 'Forgot Password' recovery flow.
*   **Product Catalog & Discovery:** A grid-based storefront featuring high-resolution images, real-time search functionality, category filtering (e.g., Footwear, Accessories), and data pagination.
*   **Shopping Cart & Checkout:** A session-based shopping cart allowing users to stage multiple items and sizes before placing a finalized order.
*   **Order Tracking:** A dedicated Customer Dashboard where users can view the status of their past and current orders.
*   **Admin Dashboard & Analytics:** A secure, role-protected control panel displaying real-time statistics (Total Sales, Active Users, Orders per day).
*   **Inventory Management:** Full CRUD interface for Admins to manage the product database and upload images.

## 4. Use Cases
**Use Case 1: Customer Browsing and Purchasing**
1. Customer logs into the application.
2. Customer uses the search bar or category filters to find a specific pair of sneakers.
3. Customer selects their size and clicks "Add to Cart".
4. Customer proceeds to Checkout, confirms their details, and places the order.
5. The system records the order in the database and updates the Customer's personal dashboard.

**Use Case 2: Admin Fulfilling an Order**
1. Admin logs into the secure control panel.
2. Admin navigates to the "Manage Orders" module.
3. Admin views the list of recent orders and identifies a "Pending" order.
4. Admin ships the physical product to the customer.
5. Admin updates the order status in the database from "Pending" to "Shipped".

**Use Case 3: Admin Adding New Inventory**
1. Admin logs into the dashboard and navigates to "Manage Products".
2. Admin clicks "Add New Product" and fills out the name, description, price, and category.
3. Admin uploads a high-resolution image of the new clothing item.
4. The system securely saves the image to the server, adds the record to the MySQL database, and the product immediately appears live on the storefront for customers.
