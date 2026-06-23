# Opportunities Module

## Purpose

Connects students with jobs, internships, scholarships, and competitions through discovery and application management.

---

## Domain Model

### Primary Aggregate Root
`Opportunity`

### Key Entities & Value Objects
> To be documented as the module is implemented. Refer to \.memory/domain-glossary.md\ for canonical definitions.

---

## Use Cases

- `PostOpportunity`
- `ApplyToOpportunity`
- `UpdateApplicationStatus`
- `SearchOpportunities`
- `SaveOpportunity`


---

## Domain Events Published

- `OpportunityPosted`
- `OpportunityApplied`
- `ApplicationStatusChanged`


---

## Public Contracts (Exposed to Other Modules)

None

---

## Dependencies

Shared, Skills (skill-based matching)

---

## Module Layer Structure

```
Opportunities/
├── Domain/
│   ├── Entities/          # Aggregate roots and child entities
│   ├── ValueObjects/      # Immutable value types
│   ├── Events/            # Domain events published by this module
│   ├── Enums/             # Domain enumerations
│   ├── Policies/          # Business rule policies
│   ├── Specifications/    # Specification pattern query objects
│   ├── Contracts/         # Interfaces exposed to other modules
│   └── Services/          # Pure domain services
├── Application/
│   ├── UseCases/          # One class per use case
│   ├── Commands/          # CQRS write-side commands
│   ├── Queries/           # CQRS read-side queries
│   ├── DTOs/              # Data Transfer Objects
│   ├── Actions/           # Single-action orchestrators
│   └── Mappers/           # Entity to DTO mapping
├── Infrastructure/
│   ├── Persistence/       # Eloquent models, migrations, seeders
│   ├── Repositories/      # Concrete repository implementations
│   ├── Integrations/      # Third-party API adapters
│   ├── Notifications/     # Notification implementations
│   ├── Cache/             # Caching strategies
│   └── Search/            # Search index adapters
├── Presentation/
│   ├── Controllers/       # Thin HTTP controllers (max 100 lines)
│   ├── Requests/          # Form Request validation
│   ├── Resources/         # API Resource transformers
│   ├── Policies/          # HTTP authorization policies
│   └── Routes/            # Module route files
├── Tests/
│   ├── Unit/              # Pure domain unit tests
│   ├── Feature/           # HTTP feature tests
│   └── Integration/       # Cross-module integration tests
└── Docs/                  # Module-specific documentation
```

---

## Coding Standards

All code in this module must follow:
- \.memory/coding-standards.md\
- \.memory/domain-glossary.md\ for naming
- ADR-002 for DDD rules
- ADR-003 for module boundary rules
- ADR-004 for event conventions
- ADR-005 for database conventions

---

## Status

Phase: **Phase 0 — Foundation**
Implementation: **Not started** — skeleton only.
