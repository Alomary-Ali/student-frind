# Database Rules — Student Success Platform (SSP)

**Last Updated:** 2026-06-16
**Authority:** Database Architect / Chief Architect
**Enforcement:** Database migrations + code reviews

---

## 1. Database Architecture

### Database System

- **Primary:** MySQL 8.0+
- **Alternative:** PostgreSQL 14+ (supported)
- **Version:** MySQL 8.0+ / PostgreSQL 14+

### Connection Management

- **Connection Pooling:** Use connection pooling
- **Max Connections:** 100 (adjust based on load)
- **Connection Timeout:** 30 seconds
- **Query Timeout:** 60 seconds
- **Read Replicas:** For read-heavy operations

---

## 2. Schema Design

### Naming Conventions

- **Tables:** snake_case, plural (e.g., `students`, `course_enrollments`)
- **Columns:** snake_case (e.g., `student_id`, `created_at`)
- **Indexes:** `idx_table_column` (e.g., `idx_students_email`)
- **Foreign Keys:** `fk_table_column` (e.g., `fk_enrollments_student_id`)
- **Constraints:** `chk_table_condition` (e.g., `chk_students_gpa_range`)

### Primary Keys

- **Type:** UUID v4 (string)
- **Naming:** `id`
- **Example:** `id VARCHAR(36) PRIMARY KEY`

### Foreign Keys

- **Naming:** `{table}_id`
- **Type:** UUID (matching referenced table)
- **Nullable:** Only if relationship is optional
- **On Delete:** CASCADE or SET NULL (never RESTRICT without reason)

### Timestamps

- **created_at:** TIMESTAMP, NOT NULL, DEFAULT NOW()
- **updated_at:** TIMESTAMP, NOT NULL, DEFAULT NOW(), ON UPDATE NOW()
- **deleted_at:** TIMESTAMP NULL (soft deletes)

---

## 3. Column Types

### Common Column Types

| Data Type | PostgreSQL | MySQL | Usage |
|-----------|------------|-------|-------|
| UUID | UUID | CHAR(36) | Primary keys, foreign keys |
| String | VARCHAR(255) | VARCHAR(255) | Names, emails, descriptions |
| Text | TEXT | TEXT | Long text content |
| Integer | INTEGER | INT | Numeric values |
| Decimal | DECIMAL(10,2) | DECIMAL(10,2) | Monetary values, GPA |
| Boolean | BOOLEAN | TINYINT(1) | True/false values |
| Date | DATE | DATE | Dates without time |
| DateTime | TIMESTAMP | TIMESTAMP | Dates with time |
| JSON | JSONB | JSON | Structured data |
| Array | TEXT[] | JSON | Arrays (PostgreSQL only) |

### Column Constraints

```sql
-- Example table definition
CREATE TABLE students (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    gpa DECIMAL(3,2) NOT NULL CHECK (gpa >= 0 AND gpa <= 4),
    status VARCHAR(50) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP NULL
);

-- Indexes
CREATE INDEX idx_students_email ON students(email);
CREATE INDEX idx_students_status ON students(status);
CREATE INDEX idx_students_deleted_at ON students(deleted_at) WHERE deleted_at IS NULL;
```

---

## 4. Indexing Strategy

### Index Types

- **B-Tree Index:** Default for most columns
- **Unique Index:** For unique constraints
- **Composite Index:** For multi-column queries
- **Partial Index:** For filtered queries
- **GIN Index:** For JSON/array columns

### Indexing Guidelines

- **Primary Keys:** Automatically indexed
- **Foreign Keys:** Always indexed
- **Unique Columns:** Unique index
- **Frequently Queried:** Index columns in WHERE, JOIN, ORDER BY
- **Composite Indexes:** For columns used together
- **Avoid Over-Indexing:** Indexes slow down writes

### Examples

```sql
-- Single column index
CREATE INDEX idx_students_email ON students(email);

-- Composite index
CREATE INDEX idx_enrollments_student_term ON course_enrollments(student_id, academic_term);

-- Partial index (PostgreSQL)
CREATE INDEX idx_active_students ON students(status) WHERE deleted_at IS NULL;

-- Unique index
CREATE UNIQUE INDEX idx_students_email ON students(email);
```

---

## 5. Migration Rules

### Migration Structure

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->decimal('gpa', 3, 2);
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
```

### Migration Guidelines

- **Reversible:** Must have both up() and down()
- **Non-Destructive:** Never drop data in production
- **Backward Compatible:** New migrations must work with old code
- **Tested:** Test migrations in development first
- **Documented:** Add comments for complex changes

### Migration Workflow

```bash
# Create migration
php artisan make:migration create_students_table

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Fresh migration (drop all tables)
php artisan migrate:fresh
```

---

## 6. Query Optimization

### Query Guidelines

- **Use Eloquent:** For simple queries
- **Use Query Builder:** For complex queries
- **Use Raw SQL:** Only when necessary (with parameter binding)
- **Avoid N+1:** Use eager loading
- **Limit Results:** Use pagination
- **Select Specific Columns:** Avoid SELECT *

### Examples

```php
// Good: Eager loading
$students = Student::with('enrollments.course')->get();

// Good: Select specific columns
$students = Student::select('id', 'name', 'email')->get();

// Good: Pagination
$students = Student::paginate(20);

// Bad: N+1 query
$students = Student::all();
foreach ($students as $student) {
    $enrollments = $student->enrollments; // N+1 query
}

// Bad: Select all columns
$students = Student::all();
```

### Query Performance

- **Explain Analyze:** Use to analyze query performance
- **Slow Query Log:** Enable for production monitoring
- **Query Cache:** Use Redis for frequently accessed data
- **Database Indexes:** Ensure proper indexing

---

## 7. Data Integrity

### Constraints

- **NOT NULL:** Required columns
- **UNIQUE:** Unique values
- **CHECK:** Custom validation rules
- **FOREIGN KEY:** Referential integrity
- **EXCLUDE:** PostgreSQL exclusion constraints

### Examples

```sql
-- Check constraint
ALTER TABLE students
ADD CONSTRAINT chk_students_gpa_range
CHECK (gpa >= 0 AND gpa <= 4);

-- Foreign key constraint
ALTER TABLE course_enrollments
ADD CONSTRAINT fk_enrollments_student_id
FOREIGN KEY (student_id) REFERENCES students(id)
ON DELETE CASCADE;

-- Unique constraint
ALTER TABLE students
ADD CONSTRAINT uq_students_email
UNIQUE (email);
```

### Data Validation

- **Application Level:** Validate before database insert
- **Database Level:** Use constraints as last line of defense
- **Triggers:** Use for complex validation (rare)
- **Stored Procedures:** Use for complex operations (rare)

---

## 8. Backup and Recovery

### Backup Strategy

- **Full Backups:** Daily
- **Incremental Backups:** Hourly
- **Binary Logs:** Continuous
- **Retention:** 30 days for daily, 7 days for hourly

### Backup Storage

- **Local:** On database server
- **Remote:** Cloud storage (S3, etc.)
- **Encrypted:** All backups encrypted
- **Tested:** Regular restore tests

### Recovery Procedures

1. **Identify Failure:** Determine what needs recovery
2. **Select Backup:** Choose appropriate backup
3. **Restore:** Restore from backup
4. **Verify:** Verify data integrity
5. **Document:** Document recovery process

---

## 9. Database Security

### Access Control

- **Least Privilege:** Minimum necessary access
- **Separate Users:** Application user vs. admin user
- **Connection Encryption:** SSL/TLS required
- **Network Security:** Database server in private network

### User Management

```sql
-- Create application user
CREATE USER ssp_app WITH PASSWORD 'secure_password';

-- Grant necessary permissions
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO ssp_app;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO ssp_app;

-- Create read-only user
CREATE USER ssp_readonly WITH PASSWORD 'secure_password';
GRANT SELECT ON ALL TABLES IN SCHEMA public TO ssp_readonly;
```

### Data Encryption

- **At Rest:** Database encryption (TDE)
- **In Transit:** SSL/TLS encryption
- **Column Encryption:** Encrypt sensitive columns
- **Key Management:** Secure key storage

---

## 10. Database Monitoring

### Metrics to Monitor

- **Connection Count:** Active database connections
- **Query Performance:** Slow query log
- **Disk Usage:** Database storage usage
- **Memory Usage:** Buffer pool hit ratio
- **Lock Contention:** Lock wait time
- **Replication Lag:** Replication delay (if applicable)

### Monitoring Tools

- **Laravel Telescope:** Application monitoring
- **pgAdmin / MySQL Workbench:** Database monitoring
- **New Relic / Datadog:** APM monitoring
- **Custom Dashboards:** Grafana, etc.

---

## 11. Database Testing

### Test Database

- **Separate Database:** Use test database
- **Migrations:** Run migrations before tests
- **Seeders:** Use seeders for test data
- **Cleanup:** Clean up after tests

### Example

```php
<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_student(): void
    {
        $student = Student::factory()->create();

        $this->assertDatabaseHas('students', [
            'id' => $student->id->value(),
            'email' => $student->email,
        ]);
    }

    public function test_email_must_be_unique(): void
    {
        Student::factory()->create(['email' => 'test@example.com']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Student::factory()->create(['email' => 'test@example.com']);
    }
}
```

---

## 12. Database Documentation

### Schema Documentation

- **ER Diagrams:** Entity-relationship diagrams
- **Data Dictionary:** Column descriptions
- **Relationship Documentation:** Table relationships
- **Index Documentation:** Index usage

### Change Documentation

- **Migration Comments:** Document migration purpose
- **Change Log:** Track schema changes
- **Impact Analysis:** Document change impact
- **Rollback Plan:** Document rollback procedure

---

## Enforcement

### Code Review

- **Migration Review:** Review all migrations
- **Query Review:** Review complex queries
- **Performance Review:** Review performance impact
- **Security Review:** Review security implications

### CI/CD

- **Migration Tests:** Test migrations in CI
- **Performance Tests:** Test query performance
- **Security Scans:** Scan for vulnerabilities
- **Documentation Check:** Ensure documentation updated

---

## References

- Architecture: `.memory/architecture.md`
- Coding Standards: `.memory/coding-standards.md`
- ADR-005: `docs/adr/ADR-005-database-conventions.md`
- PostgreSQL Documentation: https://www.postgresql.org/docs/
- MySQL Documentation: https://dev.mysql.com/doc/
