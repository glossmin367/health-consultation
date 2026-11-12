# Online Health Consultation Form

A small website where a patient fills in a medical consultation request form. The form is
validated in the browser (JavaScript) and again on the server (PHP). Valid requests are saved
in a MySQL database, and a simple admin page lists all submitted requests.

This project was built to meet the course requirements:

- **Dynamic element:** live form validation + PHP saving to MySQL + an admin page that reads from the database.
- **Responsive:** Bootstrap 5 grid, works on phone, tablet and desktop.
- **Hosted locally:** XAMPP (Apache + PHP + MySQL) at `http://localhost/health-consultation`.
- **Version control:** Git / GitHub.

---

## Tools Used

| Category | Choice |
| --- | --- |
| Editor | Visual Studio Code |
| Local server | XAMPP (Apache + PHP + MySQL) |
| Database | MySQL + phpMyAdmin |
| Design framework | Bootstrap 5 (CDN) |
| Version control | Git + GitHub |
| Languages | HTML, CSS, JavaScript, PHP |
| AI coding agent | OpenCode |

---

## Folder Structure

```
health-consultation/
├── index.php              (consultation form)
├── submit.php             (server validation + save to database)
├── success.php            (confirmation page)
├── admin.php              (admin login + list of requests)
├── logout.php             (ends admin session)
├── config/
│   ├── db.php             (database connection)
│   └── admin.php          (admin username and password)
├── assets/
│   ├── css/style.css      (small custom styles)
│   └── js/script.js       (client-side validation)
├── database/
│   └── health_consultation.sql
├── docs/
│   └── PRD.md
├── .gitignore
└── README.md
```

---

## Installation Steps

1. Install **XAMPP** from apachefriends.org.
2. Open the **XAMPP Control Panel** and start **Apache** and **MySQL**.
3. Copy the `health-consultation` folder into your XAMPP `htdocs` folder, for example:
   ```
   C:\xampp\htdocs\health-consultation
   ```
   (If your XAMPP is installed elsewhere, use that install's `htdocs` folder instead,
   e.g. `C:\xampp_new\htdocs\health-consultation`.)
4. Install **VS Code** and open the project folder.
5. Install **Git** and create a **GitHub** account if you want version control.

---

## How to Import the Database

1. Make sure **MySQL** is running in the XAMPP Control Panel.
2. Open `http://localhost/phpmyadmin` in your browser.
3. Click the **Import** tab.
4. Choose the file `database/health_consultation.sql`.
5. Click **Go**.

This creates the `health_consultation` database and the `consultations` table.

---

## How to Run

1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Open the form in your browser:
   ```
   http://localhost/health-consultation
   ```
3. Fill in the form and submit. On success you are taken to the confirmation page with a
   reference number.
4. Open the admin page:
   ```
   http://localhost/health-consultation/admin.php
   ```

### Admin Login

| Field | Value |
| --- | --- |
| Username | `admin` |
| Password | `admin123` |

> **Note:** These credentials are hard-coded in `config/admin.php` and are for **demo purposes
> only**. Do not use this login method in a real application.

---

## Testing Checklist

- [ ] Form loads on desktop and on a phone-sized browser window.
- [ ] Submitting an empty form shows errors for all required fields.
- [ ] Invalid email is rejected.
- [ ] Short phone number is rejected.
- [ ] Age of 0 or 200 is rejected.
- [ ] A past preferred date is rejected.
- [ ] Symptoms shorter than 10 characters are rejected.
- [ ] The consent checkbox must be ticked.
- [ ] A valid submission redirects to the success page and shows a reference number.
- [ ] The new row appears in phpMyAdmin and on the admin page.
- [ ] With JavaScript turned off, the server-side errors still appear.
- [ ] Trying `<script>` as a name does not run any script (it is escaped).
- [ ] The admin page requires login, and the table scrolls sideways on mobile.
- [ ] The admin can toggle a request between **Pending** and **Reviewed**.

---

## Security Notes

- Every database query uses **PDO prepared statements** to prevent SQL injection.
- All data printed on a page is escaped with `htmlspecialchars` to prevent script injection.
- Every field is validated again on the server, because JavaScript can be bypassed.
- Raw database errors are never shown to the user.
- No real patient data is stored in this demo, and no real passwords are committed to GitHub.

---

## Notes

- Bootstrap 5 is loaded from the official CDN. If there is no internet during the demo,
  download the Bootstrap files into `assets/` and link them locally.
- The admin status toggle (Pending / Reviewed) is the optional stretch feature.
