Federal University Dutse - OIRMF

Online Incident Reporting and Management Framework

A secure, enterprise-grade Single Page Application (SPA) designed to manage, report, and adjudicate examination malpractice cases at the Federal University Dutse.

🏗 Architecture

This project utilizes a highly portable 5-File Monolithic SPA Architecture:

Backend: PHP 8.1+ & MySQL 8.0+

Frontend: React 18 (via Babel Standalone CDN) & Tailwind CSS

Core Files

api.php: The master backend router. Handles DB auto-generation, authentication, and all REST endpoints.

index.php: The Public SPA. Contains the Landing Page and Login/Registration portals.

admin.php: The Admin SPA. Full CRUD management, 360° Case Dossiers, and System Analytics.

eo.php: The Exam Officer SPA. Incident triage, Case initiation, and Printable Notice Board Reports.

users.php: The Portal SPA. Role-aware dashboards for Invigilators, HODs, and Committee Members.

🚀 Hosting on GitHub Codespaces

GitHub Codespaces allows you to instantly run this platform in a cloud-based development environment with zero local setup.

Step-by-Step Guide

1. Create the Repository

Upload all 5 PHP files (api.php, index.php, admin.php, eo.php, users.php) into the root of a new GitHub Repository.

Include the .devcontainer folder generated alongside this README.

2. Launch the Codespace

On your GitHub repository page, click the green Code button.

Switch to the Codespaces tab.

Click Create codespace on main.

Wait for the environment to build. The .devcontainer configuration will automatically install Apache, PHP 8.2, and MySQL.

3. Access the Platform

Once the Codespace is running, Visual Studio Code will open in your browser.

Look at the bottom terminal panel and click on the Ports tab.

Find Port 80 (Web Application).

Click the "Globe" icon (Open in Browser) or the Local Address link next to Port 80.

You will instantly see the FUD OIRMF Landing Page!

4. Auto-Database Setup & Login
The system is designed to automatically migrate and seed its own database the first time the API is accessed.

Click Staff Portal to go to the login screen.

Log in using the default Seed credentials below. (The act of attempting to log in will trigger api.php to generate all 13 database tables and seed the demo data).

🔑 Default Seed Credentials

Use these accounts to test the different role-based workflows:

Role

Email

Password

System Administrator

admin@fud.edu.ng

Admin@1234

Exam Officer

officer@fud.edu.ng

Officer@1234

Invigilator

invigilator@fud.edu.ng

Invigi@1234

HOD / Dean

hod@fud.edu.ng

Hod@12345

Committee Member

committee@fud.edu.ng

Commit@1234

🔒 Security Features

CSRF Protection: Cryptographically secure, session-bound tokens required for all state-changing requests.

RBAC: Strict Server-Side Role-Based Access Control.

SQL Injection Prevention: 100% usage of PDO prepared statements.

Password Security: Cost-factor 12 bcrypt hashing for all personnel.