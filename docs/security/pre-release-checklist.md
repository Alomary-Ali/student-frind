# Security Pre-Release Checklist
## رفيق الطالب — يجب اجتياز كل بند قبل أي إطلاق

---

## A. Authentication & Session

| البند | المسؤول | تم ✅ |
|------|---------|------|
| كل routes الداشبورد تحت `middleware('auth')` | Backend Dev | |
| كل routes الضيوف تحت `middleware('guest')` | Backend Dev | |
| Rate limiting على login: `throttle:5,1` | Backend Dev | |
| Logout عبر POST + CSRF فقط | Backend Dev | |
| `SESSION_ENCRYPT=true` في `.env` | DevOps | |
| Session lifetime مناسب (120 دقيقة) | DevOps | |
| `remember_token` يُجدَّد عند الـ Logout | Backend Dev | |

## B. Environment & Configuration

| البند | المسؤول | تم ✅ |
|------|---------|------|
| `APP_DEBUG=false` | DevOps | |
| `APP_ENV=production` | DevOps | |
| `APP_KEY` مولَّد وغير مشارَك | DevOps | |
| `DB_PASSWORD` قوي ومعقد (16+ حرف) | DevOps | |
| `.env` غير مُدرَج في Git | DevOps | |
| لا API keys أو secrets في الكود | All Devs | |

## C. Codebase Security

| البند | المسؤول | تم ✅ |
|------|---------|------|
| لا ملفات PHP فضفاضة في جذر المشروع | Tech Lead | |
| لا `dd()`, `dump()`, `var_dump()` في production | All Devs | |
| لا raw SQL بدون bindings | Backend Dev | |
| لا `DB::statement('DROP ...')` في migrations | Backend Dev | |
| كل FormRequests تحتوي validation rules | Backend Dev | |
| كل API endpoints تحت `auth:sanctum` (عدا /login) | Backend Dev | |

## D. Infrastructure

| البند | المسؤول | تم ✅ |
|------|---------|------|
| `composer audit` → لا vulnerabilities حرجة | DevOps | |
| HTTPS مُفعَّل (SSL/TLS) | DevOps | |
| Database backups مُجدوَلة يومياً | DevOps | |
| Logs لا تحتوي على sensitive data | Backend Dev | |
| `QUEUE_CONNECTION=redis` (ليس database) | DevOps | |
| `CACHE_STORE=redis` (ليس database) | DevOps | |

## D. CI/CD Gates (تلقائية)

- [ ] PHPStan Level 6 → يمر
- [ ] Laravel Pint → يمر
- [ ] PHPUnit → 100% يمر
- [ ] Coverage >= 80%
- [ ] لا ملفات خطرة في الجذر
- [ ] Auth middleware check → يمر

---
**التوقيع:** _________________________ **التاريخ:** _________
