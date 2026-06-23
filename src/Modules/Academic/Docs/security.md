# Academic Module — Security Considerations

**Last Updated:** 2026-06-16

---

## Authentication

- Protected endpoints require Laravel Sanctum Bearer token
- Public read endpoints (student profile, courses list) exposed for integration; tighten in production via API gateway policies

## Authorization

| Action | Student | Advisor | Admin |
|--------|---------|---------|-------|
| View own profile | ✅ | ✅ | ✅ |
| Create student profile | ❌ | ❌ | ✅ (open endpoint — restrict in prod) |
| Create course | ❌ | ❌ | ✅ |
| Assign plan | ❌ | ✅ | ✅ |
| Enroll | Own only* | ✅ | ✅ |
| Record grade | ❌ | ✅ | ✅ |

*Ownership validation to be enforced via policy middleware in next iteration.

## Input Validation

- All write endpoints use Form Request validation
- UUID format enforced on all ID fields
- Grade values restricted to `GradeLetter` enum
- Mass assignment prevented via Eloquent `$fillable` whitelist

## Data Protection

- UUID primary keys prevent sequential ID enumeration
- Student profiles linked to users via FK — no duplicate profiles per user
- Audit log captures actor, action, old/new values for critical operations

## Transaction Safety

- Enrollment, plan assignment, and grade recording use `TransactionManagerInterface`
- Partial updates forbidden on multi-entity operations

## Recommendations for Production

1. Add role-based middleware on all write endpoints
2. Enforce student ownership validation (student can only access own data)
3. Rate-limit public endpoints
4. Enable HTTPS-only in production
