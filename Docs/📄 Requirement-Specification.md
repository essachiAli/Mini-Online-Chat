# 📄 Requirement Specification (RS)

## Project Title

**Mini Online Chat – Link-Based (WhatsApp-Style)**

---

## 1. Project Overview

This project aims to develop a **minimal, single-page online chat application** accessible via a **shared URL**. Any user with the link can participate in the chat without authentication.

The application is designed to **practice AJAX** in a real Laravel environment while applying **clean architecture principles** using a **Service Layer** between controllers and models.

---

## 2. Objectives

* Practice **AJAX-based communication** in Laravel
* Implement a **Service Layer architecture**
* Separate concerns: routes → controller → service → model
* Build a WhatsApp-like chat UI using **Tailwind CSS**
* Simulate real-time messaging using **AJAX polling**

---

## 3. Scope

### 3.1 In Scope

* One public chat page
* Link-based access (no authentication)
* Nickname-based message sending
* AJAX message sending and fetching
* Service layer handling business logic
* Minimal database schema

### 3.2 Out of Scope

* User accounts or login
* Message encryption
* WebSockets / broadcasting
* File or media sharing
* Message deletion or editing

---

## 4. Functional Requirements

### 4.1 Chat Access

* The system shall allow access via a public URL
* Users shall enter a nickname before sending messages
* No authentication is required

---

### 4.2 Send Message

* The system shall allow users to send text messages
* Messages shall be submitted via **AJAX**
* Page reload shall not occur
* Message validation shall be handled by the Service Layer

Each message must contain:

* Username (nickname)
* Message content
* Timestamp

---

### 4.3 Receive Messages

* The system shall retrieve messages using **AJAX polling**
* New messages shall appear automatically
* Messages shall be ordered chronologically

---

### 4.4 Message Display

* User messages appear on the right
* Other users’ messages appear on the left
* Each message displays:

  * Sender name
  * Content
  * Time sent

---

## 5. Non-Functional Requirements

### 5.1 Performance

* Message sending response time < 1 second
* Polling interval: 2–3 seconds

### 5.2 Usability

* Single-page interface
* WhatsApp-style chat layout
* Mobile-responsive UI using Tailwind CSS

### 5.3 Security

* Input validation (required, max length)
* No sensitive data storage
* Public access only

---

## 6. Technical Requirements

### 6.1 Backend

* Laravel (latest stable)
* Service Layer architecture
* MVC + Services pattern
* MySQL or SQLite database

### 6.2 Frontend

* Blade templating engine
* Tailwind CSS
* JavaScript (Fetch API or Axios)
* AJAX polling

---

## 7. Database Requirements

### Table: `messages`

| Field      | Type      | Description     |
| ---------- | --------- | --------------- |
| id         | bigint    | Primary key     |
| username   | string    | Sender nickname |
| content    | text      | Message text    |
| created_at | timestamp | Sent time       |
| updated_at | timestamp | Auto            |

---

## 8. System Architecture (Service-Based)

### Architecture Pattern

**Layered Architecture with Service Layer**

---

### 8.1 Architecture Layers

#### 1️⃣ Presentation Layer

* Blade views
* Tailwind CSS
* JavaScript (AJAX requests)

Responsibilities:

* UI rendering
* Sending AJAX requests
* Displaying chat messages

---

#### 2️⃣ Routing Layer

* Laravel routes (`web.php`, `api.php`)

Responsibilities:

* Map URLs to controllers
* Define chat endpoints

---

#### 3️⃣ Controller Layer

* ChatController

Responsibilities:

* Receive HTTP requests
* Delegate logic to Service Layer
* Return JSON or views

⚠️ **No business logic here**

---

#### 4️⃣ Service Layer

* ChatService

Responsibilities:

* Handle chat business logic
* Validate input
* Create and retrieve messages
* Interact with models

✅ **Core logic lives here**

---

#### 5️⃣ Data Access Layer

* Message Model (Eloquent)
* Database

Responsibilities:

* Persist messages
* Query message data

---

### 8.2 Architecture Flow

```
Browser (AJAX)
   ↓
Routes
   ↓
Controller
   ↓
Service
   ↓
Model (Database)
```

---

## 9. API Endpoints

| Method | Endpoint  | Description           |
| ------ | --------- | --------------------- |
| GET    | /chat     | Display chat page     |
| GET    | /messages | Fetch messages (AJAX) |
| POST   | /messages | Send message (AJAX)   |

---

## 10. User Flow

1. User opens chat link
2. User enters nickname
3. User types message
4. AJAX request sent to server
5. Controller delegates to Service
6. Message stored in database
7. Messages fetched periodically

---

## 11. Constraints

* One chat room only
* One-page UI
* No authentication
* No real-time sockets
* Minimal implementation

---

## 12. Success Criteria

* Messages send without page reload
* Messages appear automatically
* Clean Service Layer separation
* Code follows Laravel best practices
* Project completed in under 2 hours

---

## 13. Future Enhancements (Optional)

* Multiple chat rooms
* WebSockets (Laravel Echo)
* Message reactions
* User avatars

---

