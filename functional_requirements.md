Functional Requirements Specification
1. Public Interface & Landing Page Module
    • FR-WEB-01 (Landing Page): The system shall feature a public-facing landing page containing:
        ◦ Hero Section: School Name, Logo, and a primary Call to Action (CTA) button labeled "Access Portal" or "Login".
        ◦ About Section: A brief description of the TSHS system.
        ◦ Public Announcements: A section displaying pinned announcements (e.g., "Enrollment Dates", "Suspension of Classes") created by the Admin.
        ◦ Footer: Contact information and address of Taysan Senior High School.
    • FR-WEB-02 (Call to Action): The CTA button shall redirect users to the secure Login Modal or Login Page.
    • FR-WEB-03 (Responsiveness): The landing page must be fully responsive (mobile-friendly) to allow parents/students to check updates on phones.
2. Authentication & Authorization Module (Updated)
    • FR-AUTH-01 (Hybrid Login): The system shall allow users to authenticate using one single input field that accepts either:
        ◦ Email Address (e.g., juandelacruz@deped.gov.ph)
        ◦ Login ID (e.g., Student LRN 109823901 or Employee ID T-2025-01).
    • FR-AUTH-02 (Logic): The system shall automatically detect if the input is an email format or a numeric/alphanumeric ID and validate accordingly against the database.
    • FR-AUTH-03 (Role Redirection): Upon successful login, the system shall redirect the user to their specific dashboard based on their role (Admin, Teacher, or Student).
    • FR-AUTH-04 (Session): The system shall automatically log users out after 30 minutes of inactivity.

3. Super Admin (Management & Oversight)
    • FR-SA-01: Manage (Create/Edit/Disable) Academic, Registrar, and Technical Admin accounts.
    • FR-SA-02: View Global Dashboard statistics (Total Enrollees, Active Teachers, System Status).
    • FR-SA-03: Post Public Announcements that appear on the Landing Page.
    • FR-SA-04: Override access for all other admin modules in emergencies.
4. Academic Admin (Curriculum & Faculty)
    • FR-AA-01: Manage Teacher Accounts (Create/Edit/Deactivate).
    • FR-AA-02: Manage Academic Structure:
        ◦ School Years (Open/Close).
        ◦ Strands (STEM, ABM, HUMSS, TVL).
        ◦ Sections (Create sections per grade level).
        ◦ Subjects (Manage list of Core, Applied, and Specialized subjects).
    • FR-AA-03 (Subject Loading): Assign Teachers to specific Classes (Subject + Section + Schedule).
5. Registrar Admin (Records & Enrollment)
    • FR-RA-01: Manage Student Accounts (Create/Edit/Deactivate).
    • FR-RA-02 (Enrollment): Enroll students into specific Sections and Strands.
    • FR-RA-03 (Grade Validation):
        ◦ View grades submitted by Teachers.
        ◦ Approve (Publish to Student Portal).
        ◦ Return (Send back to Teacher for correction).
    • FR-RA-04 (Grade Override): Manually edit a grade in the database with a required "Reason for Change" log.
    • FR-RA-05: Generate Reports (Form 137, Form 138/Report Cards, Masterlist).
    • FR-RA-06: Verify and Process document requests (e.g., Good Moral, TOR).
6. Technical Admin (System Health)
    • FR-TA-01: Perform Database Backup and Restore operations.
    • FR-TA-02: View Audit Logs (Who logged in, who changed grades, who deleted users).
    • FR-TA-03: Reset passwords for users who cannot access their email or forgot their ID.

7. Teacher Module
    • FR-T-01: View assigned Class Schedule and Student Lists (Roster).
    • FR-T-02 (Grading):
        ◦ Input scores for Written Works, Performance Tasks, and Quarterly Assessments.
        ◦ View automated computation of initial and transmutation grades.
        ◦ Submit Grades to Registrar for approval (locks the grades).
    • FR-T-03 (Attendance): Record Daily Attendance (Present, Late, Absent, Excused) and view Monthly Summaries.
8. Student Module
    • FR-S-01: View own grades for current and previous semesters (only after Registrar approval).
    • FR-S-02: View own Attendance Record (Daily logs and Monthly Summary).
    • FR-S-03: View School Announcements.
    • FR-S-04: Request Documents (optional feature to request Form 137/Certificates).
