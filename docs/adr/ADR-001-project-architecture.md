# ADR-001 — Project Architecture: Modular Monolith

| Field       | Value                        |
|-------------|------------------------------|
| **Status**  | ✅ Accepted                  |
| **Date**    | 2026-06-15                   |
| **Authors** | Chief Architect              |
| **Deciders**| Technical Lead               |

---

## Context

The Student Success Platform (SSP) is an early-stage enterprise platform serving multiple user types (students, advisors, admins, employers, mentors) across 10 functional domains.

Key constraints at the time of this decision:
- **Small team:** 1–5 developers in the initial phase
- **Unclear domain boundaries:** Full domain understanding requires iterative development
- **Fast iteration needed:** Pilot university onboarding within 12 months
- **Long-term scalability:** Must handle 10,000+ students per university, multiple universities
- **Module isolation required:** Business domains must not leak into each other

Three architectural options were evaluated:
1. Traditional MVC Monolith (Laravel default)
2. Modular Monolith with DDD + Clean Architecture
3. Microservices

---

## Decision

**Adopt Modular Monolith with Domain-Driven Design (DDD) and Clean Architecture.**

The system is organized as a single deployable unit, divided into isolated, self-contained modules. Each module follows strict layering: Domain → Application → Infrastructure → Presentation.

Modules:
```
Academic | Productivity | Guidance | Skills | CareerProfile
Opportunities | Community | Analytics | Administration | Shared
```

---

## Rationale

### Why NOT Traditional MVC Monolith
- Business logic bleeds into models and controllers (Fat Model / Fat Controller)
- No enforced boundaries — any developer can access any table from any controller
- Refactoring becomes exponentially harder as the codebase grows
- Violates SOLID principles by design
- **Verdict: Rejected**

### Why NOT Microservices
- Requires DevOps expertise, service mesh, API gateways, distributed tracing
- Network latency between services for simple operations
- Distributed transactions are extremely complex
- Team size (1–5 devs) is too small to manage multiple deployments
- Premature optimization: SSP does not have the scale problems microservices solve yet
- **Verdict: Rejected for current phase**

### Why Modular Monolith ✅
- **Single deployment unit:** Simple to deploy, debug, and monitor
- **Enforced module isolation:** Modules communicate only via Contracts and Domain Events
- **DDD alignment:** Each module models a bounded context with its own ubiquitous language
- **Testability:** Domain layer is pure PHP — unit testable without booting Laravel
- **Migration path:** Well-isolated modules can be extracted into services later if scale demands it
- **Team-appropriate:** Full team productivity without distributed systems complexity

---

## Consequences

### Positive
- Rapid development with full team context in one codebase
- Simple debugging (single process, no network hops)
- Module boundaries enforced by convention and code review
- Domain logic is testable in isolation (pure PHP Domain layer)
- Future microservice extraction is straightforward due to clean interfaces

### Negative
- Requires strict discipline to maintain module boundaries (no tooling enforcement yet)
- All modules share the same database server (single point of failure)
- Deployment of one module requires deploying the entire application
- Large team (20+ devs) may experience merge conflicts in shared infrastructure

### Mitigations
- **For discipline:** Code review checklist includes module boundary verification
- **For DB:** Use separate database schemas per module in the future (see KI-011)
- **For deployment:** Feature flags allow deploying code without activating it

---

## Migration Path to Microservices

If SSP reaches a scale requiring service decomposition:

1. Each module already has a clean public API surface (Contracts + Events)
2. Extract module's Infrastructure layer to a separate service
3. Replace internal Contract calls with HTTP/gRPC calls
4. Replace Domain Events with a message broker (RabbitMQ / Kafka)
5. Migrate module's database tables to a dedicated database instance

Estimated effort per module: 2–4 weeks (due to clean boundaries).

---

## Alternatives Considered

| Option                  | Decision  | Reason                                          |
|-------------------------|-----------|-------------------------------------------------|
| Traditional MVC Monolith | Rejected | Fat models, no module boundaries, unmaintainable |
| Microservices            | Rejected | Premature complexity, too small a team          |
| Hexagonal Architecture   | Partially Adopted | Port/Adapter concepts adopted in Infrastructure layer |
| Event Sourcing           | Future    | Considered for Analytics module (see FI-005)   |

---

## Related

- ADR-002: DDD Rules
- ADR-003: Module Boundaries
- ADR-004: Event-Driven Design
- FI-005: Event Sourcing for Analytics
