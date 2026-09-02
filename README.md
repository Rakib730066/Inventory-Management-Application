# InvenTrack

InvenTrack is a PHP inventory management application used to manage products, stock levels, categories, and users.

## Submission Checklist

- Docker setup is included in `docker-compose.yml`.
- The database export is included in the project root as `inventory_management_export.sql`.
- The project is intended to be submitted as a zip of this whole directory.

## Run

From the project root:

```bash
docker-compose up
```

If your Docker installation uses the newer plugin syntax, `docker compose up` works too.

Open this after startup:

- App: `http://localhost:8000`
- phpMyAdmin: `http://localhost:8080`

## Login Details

- Admin: `admin@test.com` / `admin123`
- Employee: `employee@test.com` / `emp123`

phpMyAdmin:

- Username: `root`
- Password: `secret123`
- Database: `inventory_management`

## Database Export

The root file `inventory_management_export.sql` is a phpMyAdmin export of the project database.

You can import it in phpMyAdmin if needed:

1. Open `http://localhost:8080`
2. Select `inventory_management` or create it first
3. Use the Import tab
4. Upload `inventory_management_export.sql`

## Code Notes

- Front controller and route setup: `app/public/index.php`
- Layered MVC structure: `app/src/Controllers`, `app/src/Services`, `app/src/Repositories`, `app/src/Models`, `app/src/Views`
- Database connection and configuration: `app/src/Core/Database.php`
- PDO prepared statements for data access: `app/src/Repositories/ProductRepository.php`, `app/src/Repositories/CategoryRepository.php`, `app/src/Repositories/UserRepository.php`
- Validation and business rules in service layer: `app/src/Services/ProductService.php`, `app/src/Services/CategoryService.php`, `app/src/Services/UserService.php`, `app/src/Services/AuthService.php`
- Session auth and role checks: `app/src/Core/Auth.php`, `app/src/Controllers/BaseController.php`
- CSRF protection is implemented for standard form submissions: `app/src/Core/Csrf.php`
- Reusable core utilities: `app/src/Core/Session.php`, `app/src/Core/Validator.php`, `app/src/Core/View.php`

## GDPR-related work

- Only basic account data is stored for users: name, email, role, and password hash in `app/src/Repositories/UserRepository.php`
- Passwords are hashed securely and are never stored in plain text: `app/src/Services/AuthService.php`
- Password reset tokens are generated and expire after 30 minutes: `app/src/Services/AuthService.php`, `app/src/Repositories/UserRepository.php`
- Role-based access control is used so employees only access inventory features, while admins can manage users and system data: `app/src/Controllers/BaseController.php`
- Privacy Policy page is available at `/privacy`: `app/src/Controllers/LegalController.php`
- Terms and Conditions page is available at `/terms`: `app/src/Controllers/LegalController.php`
- The project includes basic GDPR-related considerations by limiting stored personal data and protecting user credentials
- Advanced GDPR features such as full personal data export and self-service account deletion are not fully implemented in this version

## WCAG-related work

- Forms use explicit labels and required inputs: `app/src/Views/auth/login.php`, `app/src/Views/products/create.php`
- Navigation uses semantic `<nav>` with `aria-label`: `app/src/Views/layout/nav.php`
- Semantic HTML structure with `<main>`, `<header>`, `<footer>`: `app/src/Views/layout/header.php`, `app/src/Views/layout/footer.php`
- All form inputs properly associated with labels using `for` attribute
- Flash messages and alerts are clearly visible
- High contrast colors meet WCAG AA standards: `app/public/assets/css/style.css`
- Responsive design using Bootstrap 5.3.3

## Notes

- The database import includes default categories and test user accounts for demonstration.
- All database queries use prepared statements to prevent SQL injection.
- Bootstrap 5 is used via CDN for responsive, accessible UI components.
