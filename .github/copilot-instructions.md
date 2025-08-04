# Copilot Instructions

This document provides guidance for AI coding agents to effectively contribute to this project.

## Project Overview

This is a web application built with the **Laravel** framework for managing and analyzing community satisfaction surveys (Indeks Kepuasan Masyarakat - IKM). The frontend is built using **Tailwind CSS** and compiled with **Vite**.

## Key Components & Architecture

The application follows the standard Laravel MVC (Model-View-Controller) architecture.

*   **Models**: Located in `app/Models/`. These represent the core entities of the application.
    *   `Kuesioner.php`: Represents a questionnaire.
    *   `Responden.php`: Represents a survey respondent.
    *   `Answer.php`: Stores the answers provided by respondents.
    *   `Unsur.php`: Represents the elements or aspects being rated in the survey.
    *   `User.php`: Standard Laravel user model for authentication.

*   **Controllers**: Located in `app/Http/Controllers/`. They handle the application's business logic. The logic is standard CRUD-based operations corresponding to the models.

*   **Views**: Blade templates are located in `resources/views/`. These files render the HTML frontend.

*   **Routes**: Web routes are defined in `routes/web.php`. API routes are in `routes/api.php`.

*   **Custom Helpers**: The project uses a custom helper file at `app/helpers.php` for global functions. Check this file for existing helper functions before adding new ones.

## Developer Workflow

1.  **Setup**:
    *   Install PHP dependencies: `composer install`
    *   Install frontend dependencies: `npm install`
    *   Create your environment file: `copy .env.example .env`
    *   Generate an application key: `php artisan key:generate`
    *   Configure your database credentials in the `.env` file.

2.  **Database**:
    *   Run migrations to create the database schema: `php artisan migrate`
    *   (Optional) Seed the database with initial data: `php artisan db:seed`

3.  **Running the Application**:
    *   Start the Laravel development server: `php artisan serve`
    *   Start the Vite development server for frontend assets: `npm run dev`

4.  **Testing**:
    *   Run the PHPUnit test suite: `php artisan test`

## Important Patterns & Dependencies

*   **Data Export**: The application uses the `maatwebsite/excel` package for exporting data to Excel. A good example is `app/Exports/IkmExport.php`, which can be used as a template for new exports.

*   **PDF Generation**: The `barryvdh/laravel-dompdf` package is used for generating PDFs from HTML views.

*   **Frontend Assets**: Frontend assets (`.js`, `.css`) are located in `resources/js` and `resources/css` and are managed by Vite. All styling should be done using Tailwind CSS utility classes. Refer to `tailwind.config.js` for theme customizations.

*   **Authentication**: The application uses standard Laravel authentication. It also includes functionality for sending OTPs via email, as seen in `app/Mail/OtpMail.php`.
