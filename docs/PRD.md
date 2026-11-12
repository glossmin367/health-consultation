# PRD: Online Health Consultation Form

Product Requirements Document, written to be given directly to OpenCode.

## 1. Overview

**Project name:** Online Health Consultation Form

**Summary:** A small website where a patient fills in a medical consultation request form. The form is validated in the browser (JavaScript) and again on the server (PHP). Valid requests are saved in a MySQL database. A simple admin page lists all submitted requests.

**Why it meets the course requirements**

| Requirement | How this project meets it |
| --- | --- |
| At least one dynamic element | Form with live validation + PHP saving to MySQL + admin page that reads from the database |
| Responsive (desktop and mobile) | Bootstrap 5 grid, tested at phone and desktop widths |
| Hosted locally | XAMPP (Apache + PHP + MySQL) at http://localhost/health-consultation |
| GitHub version control | Git repository with commits from each team member |
| Submission | Source code + documentation (this PRD, README, tools list, run steps) |

## 2. Goals and Non-Goals

**Goals**

- Keep it simple, working and easy to explain in a presentation.
- A patient can submit a consultation request in under 2 minutes.
- Invalid input is caught with clear error messages.
- Every valid submission is stored and visible on the admin page.

**Non-goals (do NOT build these)**

- No real video or chat consultation, payments, or appointment calendar.
- No user accounts or registration for patients.
- No external APIs, no frameworks like Laravel, React or Node.
- No email sending.

## 3. Tools and Environment (chosen from the course slide)

| Category | Choice | Purpose | Why this choice |
| --- | --- | --- | --- |
| Editor | Visual Studio Code | Write and read the code | Free, familiar, easy to show in a demo |
| Local server | XAMPP | Runs Apache, PHP and MySQL locally | One installer, matches the local hosting requirement |
| Database | MySQL + phpMyAdmin | Store form data | Comes with XAMPP, has a visual interface to show the table |
| Design framework | Bootstrap 5 | Responsive layout and styling | Fast, responsive by default, less custom CSS |
| Version control | Git + GitHub | Team collaboration | Required by the instructions |
| Languages | HTML, CSS, JavaScript, PHP | Front end and back end | Suggested tools in the brief |
| AI coding agent | OpenCode | Generates the code from this PRD | Used to speed up development |

**Not used:** Sublime/Notepad++ (VS Code is enough), WAMP/Laragon (XAMPP is enough), Tailwind (Bootstrap is simpler), optional APIs (not needed).

## 4. Users

- **Patient:** wants to request a medical consultation online without calling or visiting.
- **Admin / clinic staff:** wants to see the list of requests and what each patient needs.

### User stories

1. As a patient, I fill in my details and symptoms so a doctor can review my request.
2. As a patient, I see clear error messages if I make a mistake, so I can fix them.
3. As a patient, I see a confirmation after submitting.
4. As an admin, I view all consultation requests in a table, newest first.
5. (Optional) As an admin, I can mark a request as Pending or Reviewed.

## 5. Functional Requirements

### 5.1 Consultation form (index.php)

| Field | Type | Required | Validation rule |
| --- | --- | --- | --- |
| Full name | text | Yes | 3 to 60 characters, letters and spaces only |
| Email | email | Yes | Valid email format |
| Phone number | text | Yes | 10 to 15 digits (a leading + is allowed) |
| Age | number | Yes | Whole number from 1 to 120 |
| Gender | select | Yes | Male, Female or Other |
| Consultation type | select | Yes | Video call, Phone call or Chat |
| Medical department | select | Yes | General Medicine, Pediatrics, Dermatology, Gynecology, Dentistry, Mental Health |
| Preferred date | date | Yes | Today or a future date |
| Symptoms description | textarea | Yes | 10 to 500 characters |
| Consent checkbox | checkbox | Yes | Must be ticked (patient confirms the information is correct) |

The form page must also show a short notice: **This form is not for emergencies. If it is an emergency, contact your nearest hospital immediately.**

### 5.2 Validation (two layers, both required)

1. **Client side (JavaScript, in assets/js/script.js):**
   - Validate every field when the form is submitted, and also when a field loses focus.
   - Show a red error message under the field and use Bootstrap invalid styling.
   - Block submission if any field is invalid.
   - Show a live character counter for the symptoms box (for example 120 / 500).
2. **Server side (PHP, in submit.php):**
   - Re-check every rule above, because JavaScript can be bypassed.
   - Trim inputs and clean them with `htmlspecialchars`.
   - If invalid, go back to the form with the error messages and keep what the user already typed.

### 5.3 Saving the request (submit.php)

- Connect to MySQL through `config/db.php`.
- Insert the data using **prepared statements** (PDO or mysqli) to prevent SQL injection.
- Set status to `Pending` and save the date/time automatically.
- On success, redirect to `success.php`.
- On database failure, show a friendly error message, never raw errors.

### 5.4 Success page (success.php)

- Show a thank-you message and a reference number (the request ID).
- Include a button to go back and submit another request.

### 5.5 Admin page (admin.php)

- Show all requests in a responsive table: ID, name, email, phone, department, consultation type, preferred date, status, submitted time.
- Newest first. Show a message like No requests yet when empty.
- The table must scroll horizontally on mobile (Bootstrap table-responsive).
- Simple protection: a basic login using a hard-coded username and password stored in `config/admin.php`, checked with PHP sessions. Keep it simple and mention in the README that it is for demo purposes only.
- **Optional stretch (only if everything else is finished):** a button to change a request’s status between Pending and Reviewed.

## 6. Database

**Database name:** `health_consultation` **Table:** `consultations`

```sql
CREATE DATABASE IF NOT EXISTS health_consultation;
USE health_consultation;

CREATE TABLE IF NOT EXISTS consultations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(60) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  age INT NOT NULL,
  gender VARCHAR(10) NOT NULL,
  consultation_type VARCHAR(20) NOT NULL,
  department VARCHAR(50) NOT NULL,
  preferred_date DATE NOT NULL,
  symptoms TEXT NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

This SQL must also be saved as `database/health_consultation.sql` so it can be imported through phpMyAdmin.

## 7. Project Structure

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

The project folder must sit in `C:\xampp\htdocs\health-consultation` (Windows) so it runs at `http://localhost/health-consultation`.

## 8. Design and Responsiveness

- Use **Bootstrap 5** loaded from the official CDN link in the head of each page. (If there will be no internet during the demo, download the Bootstrap files into `assets/` and link them locally. Mention this in the README.)
- Include the viewport meta tag on every page.
- Layout: a single centered card for the form. It should take the full width on phones and about 700px wide on desktop.
- Calm medical look: white and light blue, with a clear heading and readable fonts. Keep custom CSS short.
- Buttons and inputs must be easy to tap on mobile.
- Must look correct at widths 360px (phone), 768px (tablet) and 1280px (desktop).

## 9. Basic Security (keep it simple, but do it)

- Prepared statements for every database query.
- `htmlspecialchars` whenever data is printed on a page.
- Server-side validation on every field.
- Never show raw database errors to the user.
- Do not store real patient data in the demo and do not push real passwords to GitHub.

## 10. Instructions for OpenCode

**Copy this whole section into OpenCode, or save this PRD in the project folder and tell OpenCode to read it.**

### 10.1 Rules

1. Use ONLY: HTML, CSS, JavaScript (plain, no libraries), PHP (plain, no framework), MySQL, Bootstrap 5. Nothing else.
2. Keep code simple, readable and beginner-friendly, because the students must explain every file in a presentation.
3. Add short comments above each important block explaining what it does.
4. Follow the file structure in section 7 exactly. Do not add extra files, folders or dependencies.
5. Do not use Composer, npm packages, Node.js or any build tools.
6. Build in the phases below, one at a time. After each phase, stop and tell me what was created and how to test it.
7. Do not implement the non-goals in section 2.
8. Use PDO with prepared statements for database access.
9. Do not run commands that install software or delete files without asking me first.

### 10.2 Build phases

**Phase 1: Setup and database** Create the folder structure, `database/health_consultation.sql` (use the SQL in section 6), `config/db.php` (connects to localhost, user root, empty password, database health\_consultation, using PDO with error handling), and `.gitignore`.

**Phase 2: The form page** Create `index.php` with the Bootstrap form containing every field in section 5.1, the emergency notice, error message areas under each field, and the symptoms character counter. If PHP sends back errors, display them and keep the old values in the fields.

**Phase 3: Client-side validation** Create `assets/js/script.js` implementing every rule in section 5.2 (validate on blur and on submit, block invalid submissions, show messages under fields). Add `assets/css/style.css` for light custom styling.

**Phase 4: Server-side validation and saving** Create `submit.php`: accept POST only, trim and validate every field with the same rules as JavaScript, insert with a prepared statement, redirect to `success.php?id=...` on success, and return to `index.php` with errors on failure. Create `success.php` showing the thank-you message and the reference number.

**Phase 5: Admin page** Create `config/admin.php`, `admin.php` (login form, session check, table of all requests newest first, table-responsive) and `logout.php`.

**Phase 6: Documentation** Create `README.md` with: project description, tools used, installation steps, how to import the database, how to run, admin login details, folder structure, and a testing checklist. Keep it short and clear.

**Phase 7 (only if time allows): Stretch** Add Pending/Reviewed status toggle on the admin page.

### 10.3 Ready-to-use first prompt for OpenCode

> Read docs/PRD.md fully. Follow section 10 (rules and phases) strictly. Start with Phase 1 only. When done, tell me what you created and how to verify it, then wait for me to say continue.

## 11. Setup on Your Laptop

1. Install **XAMPP** from apachefriends.org. Open the XAMPP Control Panel and **Start** Apache and MySQL.
2. Install **VS Code** from code.visualstudio.com.
3. Install **Git** from git-scm.com and create a **GitHub** account.
4. Install **OpenCode** by following the instructions at opencode.ai (it may require Node.js, so install the LTS version if the guide says so). Log in or add a model key as it asks.
5. Create the folder `C:\xampp\htdocs\health-consultation`, put this PRD in `docs/PRD.md`, open the folder in VS Code and open a terminal in it.
6. Run `opencode` in that terminal and give it the first prompt from section 10.3.

OpenCode writes real files into this folder. Those are your source codes, and nothing is hidden or locked away.

## 12. How to Run the Project (execution procedure for the submission)

1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Open http://localhost/phpmyadmin, click **Import**, choose `database/health_consultation.sql` and click Go.
3. Make sure the project folder is inside `htdocs`.
4. Open http://localhost/health-consultation in the browser to see the form.
5. Open http://localhost/health-consultation/admin.php to view the requests (log in with the details in the README).

## 13. GitHub Workflow for the Team

1. One member creates a GitHub repository named `health-consultation` and adds the others as collaborators.
2. Initialize locally: `git init`, `git add .`, `git commit -m "Initial project structure"`, then connect with `git remote add origin <repo-url>` and `git push -u origin main`.
3. Others run `git clone <repo-url>`.
4. Each member commits after finishing a task with a clear message, for example `Add client-side validation`, then runs `git pull` before `git push`.
5. Suggested split: one person on the form and JavaScript, one on PHP and the database, one on the admin page and documentation. Everyone commits at least once, so the history shows teamwork.

## 14. Testing Checklist

- [ ] Form loads on desktop and on a phone-sized browser window (Chrome dev tools device mode).
- [ ] Submitting an empty form shows errors for all required fields.
- [ ] Invalid email, short phone number, age of 0 or 200, a past date and too-short symptoms are each rejected.
- [ ] The consent checkbox must be ticked.
- [ ] A valid submission redirects to the success page and shows a reference number.
- [ ] The new row appears in phpMyAdmin and on the admin page.
- [ ] Turning off JavaScript in the browser still triggers server-side errors.
- [ ] Trying `<script>` as a name does not run any script (it is escaped).
- [ ] The admin page needs login, and the table scrolls sideways on mobile.

## 15. Deliverables

1. Complete source code (the project folder, zipped, plus the GitHub link).
2. Documentation of the tools used (section 3 of this PRD or the README).
3. Procedure for execution (section 12 of this PRD or the README).
4. This PRD and screenshots of the form, the success page, the admin page and the phpMyAdmin table.

## 16. Presentation Talking Points

- **Problem:** booking a consultation usually means a call or a visit. This form lets patients request one online.
- **Flow to demo:** open the form, submit wrong data to show validation, submit correct data, show the success page, show the saved row in phpMyAdmin, show the admin page, then shrink the browser to show mobile view.
- **Why these tools:** XAMPP gives a local server and a database in one install, Bootstrap makes it responsive, GitHub tracks teamwork.
- **Two-layer validation:** JavaScript gives instant feedback, PHP protects the data even if JavaScript is bypassed.
- **Security:** prepared statements stop SQL injection, `htmlspecialchars` stops script injection.
- **Be honest about the AI tool:** say you used OpenCode to speed up coding from a PRD, that you reviewed and tested the output, and that you can explain every file.

## 17. Suggested Timeline (adjust to your deadline)

| Day | Task |
| --- | --- |
| 1 | Install tools, create GitHub repo, run OpenCode Phases 1 and 2 |
| 2 | Phases 3 and 4 (validation and saving), test the main flow |
| 3 | Phases 5 and 6 (admin page and README), full testing checklist |
| 4 | Screenshots, zip the project, rehearse the presentation |
