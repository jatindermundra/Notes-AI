# AI-Powered Notes Management System (NotifyVisitors)

## Overview

This project is an AI-powered Notes Management System built using Laravel, MySQL, and OpenAI APIs.

The application provides:

* Notes CRUD APIs
* AI-powered Semantic Search using Embeddings
* AI-generated Note Summaries
* Pagination Support
* Responsive Frontend UI
* Secure API Validation
* Rate Limiting Protection

---

## Technology Stack

### Backend

* Laravel 12
* PHP 8.2+
* MySQL

### AI Integration

* OpenAI Embeddings API (`text-embedding-3-small`)
* OpenAI Chat API (`gpt-4o-mini`)


## Installation

### Clone Repository

```bash
git clone https://github.com/yourusername/notes-ai-system.git

cd notes-ai-system
```

### Install Dependencies

```bash
composer install
```

### Configure Environment

```bash
cp .env.example .env
```

Update database and OpenAI settings:

```env
DB_DATABASE=notes_ai
DB_USERNAME=root
DB_PASSWORD=

OPENAI_API_KEY=your_openai_api_key
```

### Generate Application Key

```bash
php artisan key:generate
```

### Run Migrations

```bash
php artisan migrate
```

### Start Application

```bash
php artisan serve
```

Application URL:

```text
http://127.0.0.1:8000
```

---

# Database Schema

## notes table 


# API Documentation

Base URL:

```text
http://127.0.0.1:8000/api
```

---

## 1. Create Note

### Endpoint

```http
POST /notes
```

### Request

```json
{
  "title": "Laravel Basics",
  "content": "Laravel is a PHP framework."
}
```

### Response

```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Laravel Basics",
    "content": "Laravel is a PHP framework."
  }
}
```

### Status Code

```http
201 Created
```

---

## 2. Get Notes List

### Endpoint

```http
GET /notes?page=1
```

### Response

```json
{
  "current_page": 1,
  "data": []
}
```

### Status Code

```http
200 OK
```

---

## 3. Get Single Note

### Endpoint

```http
GET /notes/{id}
```

### Example

```http
GET /notes/1
```

### Status Code

```http
200 OK
```

---

## 4. Update Note

### Endpoint

```http
PUT /notes/{id}
```

### Request

```json
{
  "title": "Updated Title",
  "content": "Updated content"
}
```

### Status Code

```http
200 OK
```

---

## 5. Delete Note

### Endpoint

```http
DELETE /notes/{id}
```

### Status Code

```http
204 No Content
```

---

## 6. Semantic Search

### Endpoint

```http
GET /notes-search?q=laravel framework
```

### Description

This endpoint uses OpenAI embeddings and cosine similarity to find notes that are semantically related to the search query.

### Example Response

```json
[
  {
    "id": 1,
    "title": "Laravel Basics",
    "score": 0.92
  }
]
```

### Status Code

```http
200 OK
```

---

## 7. Generate AI Summary

### Endpoint

```http
POST /notes/{id}/summary
```

### Example

```http
POST /notes/1/summary
```

### Response

```json
{
  "summary": "Laravel is a PHP framework used for building modern web applications."
}
```

### Status Code

```http
200 OK
```

---

# Security Measures

* Request Validation
* Eloquent ORM Protection Against SQL Injection
* Rate Limiting
* Environment Variable Configuration
* Structured JSON Responses
* Proper HTTP Status Codes

---

# AI Usage Explanation

## AI Tools Used

* ChatGPT
* OpenAI API

## AI Features Implemented

### Semantic Search

When a note is created, an embedding vector is generated using:

```text
text-embedding-3-small
```

The vector is stored in the database.

During search:

1. Query embedding is generated.
2. Cosine similarity is calculated.
3. Most relevant notes are returned.

---

### AI Summary Generation

The application uses:

```text
gpt-4o-mini
```

to generate concise summaries of note content.

---

# Architecture

```text
Frontend
    ↓
Laravel API
    ↓
MySQL Database
    ↓
OpenAI APIs
        ├─ Embeddings
        └─ Summaries
```



# Author

PHP Backend Developer Assignment Submission Given By NotifyVisitors 

Built with Laravel, MySQL, OpenAI, and AI-Assisted Development Tools.
