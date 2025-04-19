# Security Notes – Poole's Pools Web Server

This document outlines the key security measures implemented in the Poole's Pools project, developed using PHP, MySQL, and Apache.

---

## Authentication & Sessions

- Sessions are initialized with `session_start()` and used to track authenticated users.
- Upon successful login, user ID and role are stored in `$_SESSION` to differentiate between customers and technicians.
- Session checks protect restricted pages from unauthorized access (e.g., admin vs. customer dashboards).
- Session is destroyed on logout via `session_destroy()`.

---

## Password Protection

- Passwords are **hashed using `password_hash()`** on signup.
- Login uses `password_verify()` to securely compare credentials.
- Plaintext passwords are never stored or echoed in the code.

---

## SQL Injection Prevention

- All database interactions use **prepared statements** via `mysqli->prepare()` and `bind_param()` to avoid SQL injection.
- User inputs are not directly concatenated into SQL queries.

---

## Input Validation

- Basic server-side validation is enforced (e.g., `!empty()` checks on login/signup).
- JavaScript and HTML5 form validation are also used to catch invalid inputs client-side.
- Additional sanitization can be added using `filter_input()` or custom regex if needed.

---

## Role-Based Access Control

- User roles (`Customer` or `Technician`) are validated on login and stored in the session.
- Technicians are redirected to admin dashboard pages; customers stay on the main site.
- Customers are pulled from the `Customer` database table, while technician credentials are hardcoded for tighter control.

---

## File & Database Security

- The database connection file (`db.php`) uses a non-root user (`webuser`) with limited permissions.
- Sensitive information (DB password) is isolated from the frontend and not exposed.
- All server files are intended to run behind HTTPS (assumed on production hosting).

---

## What’s Not Yet Implemented

- No CSRF token protection (can be added to forms)
- No rate limiting or brute-force protection
- No email verification or password reset logic yet

---

## Implementations for Future Hardening

- Adding `CSRF` tokens to critical forms (login, signup)
- Implementing login attempt limiting

---

**Last updated:** April 2025  
**Author:** Gage Hutson  
