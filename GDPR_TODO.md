# Data Export

## Export Your Data

Users should be able to export their personal data in a machine-readable format (JSON or CSV).

### Implementation Stub

```php
// Add to LegalController

public function exportData(): void
{
    $this->requireLogin();
    
    $userId = Auth::id();
    $user = $this->userService->find($userId);
    
    // Would gather all user data and generate a JSON/CSV file
    // This stub is a placeholder for future implementation
}
```

### Route to Add

```php
$r->addRoute('GET', '/export-data', ['App\Controllers\LegalController', 'exportData']);
```

This feature allows users to obtain their personal data in a portable format.
