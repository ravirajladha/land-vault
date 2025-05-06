# LandVault

## Overview
LandVault is a Laravel-based web application for managing land documents. Users can upload PDFs, create dynamic categories (e.g., Residential, Commercial) with custom fields (e.g., Plot Number, Ownership Date), and generate detailed reports. The platform supports secure user management with role-based permissions, ensuring only authorized users can access or modify data. Built for efficiency and scalability, LandVault is a key part of my portfolio, showcasing full-stack development with Laravel.

## Features
- **Dynamic Categories & Fields**: Create custom categories and fields for document types (e.g., deeds, leases).
- **PDF Upload**: Securely upload and store land document PDFs.
- **Basic Details**: Enter metadata (e.g., document title, owner) before categorizing.
- **Reporting**: Generate reports on documents, filtered by category or field.
- **User Management**: Role-based permissions for creating users (e.g., admin, uploader) with CRUD operations.
- **Secure Access**: Permissions restrict document access to authorized users.
- **Responsive UI**: Clean, user-friendly interface for seamless navigation.

## Tech Stack
- **Backend**: Laravel (PHP MVC framework)
- **Frontend**: HTML, CSS, JavaScript, Blade templates
- **Database**: MySQL
- **File Storage**: Laravel Filesystem for PDF uploads
- **Server**: Apache/Nginx

## Prerequisites
- PHP >= 8.0
- Composer
- MySQL
- Node.js & NPM (for frontend assets)

## Installation
1. **Clone the Repository**:
   ```bash
   git clone https://github.com/ravirajladha/LandVault.git
   cd LandVault
   ```
2. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```
3. **Configure Environment**:
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update `.env` with database settings:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=landvault
     DB_USERNAME=root
     DB_PASSWORD=
     ```
4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```
5. **Run Migrations**:
   ```bash
   php artisan migrate
   ```
6. **Seed the Database** (optional):
   ```bash
   php artisan db:seed
   ```
   Populates sample categories and users for testing.
7. **Compile Frontend Assets**:
   ```bash
   npm run dev
   ```
8. **Start the Development Server**:
   ```bash
   php artisan serve
   ```
   Access at `http://localhost:8000`.

## Using .gitignore
- A `.gitignore` file is included to exclude sensitive files (e.g., `.env`, logs, PDFs).
- To apply it, ensure it’s in the project root and run:
  ```bash
  git add .gitignore
  git commit -m "Add .gitignore"
  ```
- Remove any tracked sensitive files:
  ```bash
  git rm --cached .env
  git commit -m "Remove .env from tracking"
  ```

## Demo
Explore LandVault’s features through a 5-part demo series with a custom thumbnail: [LandVault Demo Series](https://www.youtube.com/watch?v=WLCXnttiWdY&list=PLBvkqKB4HJMol-vYww0nNmnxXB4Kb0w6D&pp=gAQB)  
Videos are ordered to showcase the workflow:
1. Creating dynamic categories
2. Setting up custom fields
3. Uploading land document PDFs
4. Managing user permissions
5. Generating document reports

## Testing
- Run Laravel tests:
  ```bash
  php artisan test
  ```
- Manually test dynamic field creation, PDF uploads, and report generation.

## Contributing
Contributions are welcome! Submit a pull request or open an issue for suggestions.

## Security Notes
- Ensure `.env` is in `.gitignore` to avoid exposing credentials.
- Sanitize code and videos for sensitive data (e.g., no real land records).
- Scan for secrets before pushing:
  ```bash
  docker run -it --rm -v "$(pwd):/pwd" trufflesecurity/trufflehog git file:///pwd
  ```
- Validate PDF uploads server-side:
  ```php
  $request->validate(['document' => 'required|file|mimes:pdf|max:10240']);
  ```



## Contact
For questions or feedback, reach out to [Ravi Raj Ladha](mailto:ravirajldha.com).