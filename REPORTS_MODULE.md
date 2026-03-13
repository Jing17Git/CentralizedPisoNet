# Admin Reports Module - CentralizedPisoNet

## Overview
The Admin Reports Module provides comprehensive reporting and analytics for the PisoNet Management System, allowing administrators to monitor earnings, machine usage, and session activity.

## Features

### 1. Reports Dashboard (`/admin/reports`)
- **Summary Cards**: Display key metrics
  - Total Earnings Today
  - Total Coins Inserted
  - Active Machines
  - Total Sessions Today
- **Charts**: Visual representation of data
  - Daily Earnings Chart (Last 7 days)
  - Machine Usage Chart (Top 5 machines)
- **Quick Links**: Navigate to detailed reports

### 2. Earnings Report (`/admin/reports/earnings`)
- View detailed earnings breakdown by machine and date
- **Filters**:
  - Filter by Date
  - Filter by Machine
  - Filter by Period (Today/Week/Month)
- **Export Options**:
  - Export to PDF
  - Export to Excel
  - Print Report
- **Table Columns**:
  - Date
  - Machine ID
  - Machine Name
  - Coins Inserted
  - Minutes Purchased
  - Total Amount

### 3. Machine Usage Report (`/admin/reports/machines`)
- Comprehensive machine activity and performance
- **Table Columns**:
  - Machine ID
  - Machine Name
  - Total Sessions
  - Total Minutes Used
  - Total Coins Collected
  - Status (Online/Offline)
- **Export Options**: PDF, Excel, Print

### 4. Session History Report (`/admin/reports/sessions`)
- Complete session logs and activity
- **Filters**:
  - Filter by Date
  - Filter by Machine
- **Table Columns**:
  - Session ID
  - Machine ID
  - User Name
  - Start Time
  - End Time
  - Minutes Used
  - Remaining Time
  - Status (Active/Ended)
- **Export Options**: PDF, Excel, Print

## Database Tables

### machines
```sql
- id (primary key)
- machine_name
- machine_code (unique)
- ip_address
- location
- status (online/offline)
- timestamps
```

### coin_transactions
```sql
- id (primary key)
- machine_id (foreign key)
- coins_inserted
- minutes_purchased
- amount (decimal)
- timestamps
```

### sessions
```sql
- id (primary key)
- session_id (unique)
- pc_unit_number
- user_session_name
- start_time
- remaining_time
- status (Active/Ended)
- end_time
- timestamps
```

## Routes

```php
Route::get('/admin/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/admin/reports/earnings', [ReportController::class, 'earnings'])->name('reports.earnings');
Route::get('/admin/reports/machines', [ReportController::class, 'machines'])->name('reports.machines');
Route::get('/admin/reports/sessions', [ReportController::class, 'sessions'])->name('reports.sessions');
```

## Controller Methods

### ReportController

- **index()**: Display reports dashboard with summary statistics and charts
- **earnings()**: Show earnings report with filters and pagination
- **machines()**: Display machine usage statistics
- **sessions()**: Show session history with filters
- **getDateRange()**: Helper method to calculate date ranges for filters

## Models

### Machine
- Relationships:
  - `hasMany(Session)` via pc_unit_number
  - `hasMany(CoinTransaction)`

### CoinTransaction
- Relationships:
  - `belongsTo(Machine)`

### Session
- Uses `sessions` table
- Calculates minutes_used dynamically

## Installation

1. Run migrations:
```bash
php artisan migrate
```

2. Seed sample data (optional):
```bash
php artisan db:seed --class=ReportsSeeder
```

3. Access reports at `/admin/reports`

## Technologies Used

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Blade Templates, Tailwind CSS (via custom PisoNet theme)
- **Charts**: Chart.js
- **Database**: MySQL

## Future Enhancements

- [ ] Implement PDF export functionality
- [ ] Implement Excel export functionality
- [ ] Add more chart types (pie charts, bar charts)
- [ ] Add date range picker
- [ ] Add real-time data updates
- [ ] Add email report scheduling
- [ ] Add custom report builder
- [ ] Add comparison reports (week-over-week, month-over-month)

## Notes

- All monetary values are in Philippine Peso (₱)
- Default rate: ₱5 per coin = 5 minutes
- Reports use the custom PisoNet glassmorphism theme
- Charts are responsive and interactive
- Pagination is set to 20 items per page
