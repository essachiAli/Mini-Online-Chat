# 🚀 EXECUTION MODE – FULL PROJECT GENERATION

## SYSTEM ROLE

You are **Cloud AI in Execution Mode**.
You **generate, write, and execute real code**.
You **do NOT explain** unless inside code comments.
You **DO NOT ask questions**.
You **ONLY produce working results**.

---

## PROJECT METADATA

* **Project Name:** Online-Chat
* **Framework:** Laravel (latest stable)
* **Frontend:** Blade + Tailwind CSS
* **Backend Pattern:** Service Layer Architecture
* **Database:** Mysql (default)
* **Environment:** Local / Cloud execution

---

## CORE OBJECTIVE

Build a **minimal one-page online chat application** (WhatsApp-style) to **practice AJAX**.

* Public access via shared link
* No authentication
* Nickname-based chat
* AJAX polling (2–3 seconds)
* One chat room only

---

## ARCHITECTURE (MANDATORY)

Strict execution flow:

```
Routes → Controller → Service → Model → Database
```

### RULES (ENFORCED)

* ❌ NO business logic in controllers
* ❌ NO direct model usage inside routes
* ✅ ALL logic in Service Layer
* ✅ Controllers only delegate to services

---

## REQUIRED EXECUTION STEPS

### STEP 1 – PROJECT INITIALIZATION

* Create a new Laravel project named `Online-chat`
* Configure `.env` to use **MYSQL**
* Ensure application runs with `php artisan serve`

---

### STEP 2 – DATABASE

Create migration:

**Table:** `messages`

| Field      | Type        |
| ---------- | ----------- |
| id         | bigint (PK) |
| username   | string (50) |
| content    | text        |
| timestamps | enabled     |

Run migrations.

---

### STEP 3 – MODEL

Create Eloquent model:

* `App\Models\Message`
* Mass assignable: `username`, `content`

---

### STEP 4 – SERVICE LAYER

Create:

```
app/Services/ChatService.php
```

Responsibilities:

* Validate message input
* Store messages
* Fetch messages ordered by `created_at`
* Return collections or arrays

Validation rules:

* username: required, max 50
* content: required, max 500

---

### STEP 5 – CONTROLLER

Create:

```
app/Http/Controllers/ChatController.php
```

Rules:

* Inject `ChatService`
* No validation logic
* No Eloquent queries

Methods:

* `index()` → returns chat Blade view
* `fetchMessages()` → returns JSON
* `sendMessage()` → returns JSON

---

### STEP 6 – ROUTES

Edit `routes/web.php`:

| Method | URI       | Action                       |
| ------ | --------- | ---------------------------- |
| GET    | /chat     | ChatController@index         |
| GET    | /messages | ChatController@fetchMessages |
| POST   | /messages | ChatController@sendMessage   |

---

### STEP 7 – FRONTEND (BLADE + TAILWIND)

Create:

```
resources/views/chat.blade.php
```

Requirements:

* WhatsApp-style UI
* Message list (scrollable)
* Input at bottom
* Username input (stored in localStorage)
* Tailwind CSS only

---

### STEP 8 – AJAX LOGIC

Inside Blade:

* Use Fetch API or Axios
* POST messages without reload
* Poll `/messages` every 2–3 seconds
* Auto-scroll to latest message

---

### STEP 9 – FINAL CHECKS

* No page reload on send
* Messages appear automatically
* Architecture strictly respected
* Code is runnable immediately

---

## OUTPUT FORMAT RULES

* Write **real Laravel code**
* Create files explicitly
* Use clean naming
* Use comments only when necessary
* NO markdown explanations outside code

---

## FINAL COMMAND

> Execute all steps and deliver a **fully working Laravel chat application** following the architecture and constraints above.

🔥 **START EXECUTION NOW**

