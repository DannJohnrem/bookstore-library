# Bookstore Library Admin System

## Project Overview

The Bookstore Library Admin System is a web application built using PHP and Laravel 9. It provides functionality for managing a collection of books, allowing administrators to perform CRUD (Create, Read, Update, Delete) operations. The system includes features for validation, searching, sorting, and importing/exporting book data.

### Objectives

- Implement a CRUD system for managing books.
- Validate book entries to avoid duplicates based on the book name and author.
- Display book entries in alphabetical order.
- Provide search and sort functionality for books.
- Show individual book details in a modal.
- Implement unit tests for all endpoints.
- Enable CSV import and export of book lists.

## Requirements

### Functional Requirements

1. **CRUD Operations**:
   - **Create**: Add new books with name, author, and cover image.
   - **Read**: Display a list of books with search and sort options.
   - **Update**: Edit existing book details.
   - **Delete**: Remove books from the list.

2. **Validation**:
   - Ensure that there are no duplicate records of books with the same name and author.

3. **Display**:
   - Display books alphabetically by name.
   - Allow searching by book name and author.
   - Provide sorting options for book name and author (ascending and descending).

4. **Individual View**:
   - Open a modal showing all details of the selected book.

5. **Import/Export Functionality**:
   - Import a CSV file of book data (minimum of 500 rows).
   - Export the book list as a CSV file.

### Non-Functional Requirements

- Adhere to PHP/Laravel best practices.
- Ensure responsiveness and user-friendly design.

## Technical Stack

- **Language**: PHP
- **Framework**: Laravel 9
- **Database**: MySQL
- **Frontend**: HTML, Tailwind CSS
- **Testing**: PHPUnit
- **CSV Handling**: Laravel Excel package

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

