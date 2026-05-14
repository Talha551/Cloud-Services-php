# Cloud Services Monolith (CodeIgniter 3 + PHP 5.4 style)

This is a fresh single-project CodeIgniter setup where **frontend pages** and **backend API** both run from the same app.

## Tech
- CodeIgniter 3.1.13
- PHP 5.4 compatible coding style
- SQLite (PDO) default for quick local run

## Run
1. Open terminal in `cloud-services-ci3`
2. Run:
   - `php -S 127.0.0.1:8092`
3. Open:
   - `http://127.0.0.1:8092/`

## Default Users
- Admin: `admin@example.com` / `admin123`
- Client: `client@example.com` / `client123`

## Frontend Routes
- `/`
- `/login`
- `/signup`
- `/dashboard`
- `/services`

## API Routes
- `GET /api/health`
- `POST /api/auth/login`
- `POST /api/auth/register`
- `GET /api/auth/profile`
- `GET /api/client/services`
- `GET /api/client/services/{id}/start`
- `GET /api/client/services/{id}/stop`
- `GET /api/client/services/{id}/restart`

## Notes
- Session auth is used for both web and API in this starter.
- For production, move to HTTPS and rotate `encryption_key` in `application/config/config.php`.
- If SQLite is not available on your PHP build, switch `application/config/database.php` to MySQLi.

## Professional Features Implemented
- VPS create flow with optional custom root password (`admin/servers/create`)
- Provider root password display in service details when returned by SolusVM
- Client console page with WebSocket reconnect and fallback URL option
- Security headers via CI hooks (CSP, X-Frame-Options, etc.)
- Client support ticket module (`client/tickets`)
- Billing automation endpoint for recurring invoices and overdue suspension (demo mode)
- Audit logging table for operational events (`audit_logs`)

## Billing Mode
- Billing is currently **demo-only**.
- `Pay (Demo)` simulates payment capture and activates services without charging real money.
- Demo transaction IDs are stored in invoices for testing traceability.

## Billing Automation (Phase 2)
Run daily automation using CLI (recommended):

```bash
php index.php cron billing_daily
```

Or over HTTP with token:

```bash
http://YOUR_HOST/automation/billing/daily?token=YOUR_AUTOMATION_TOKEN
```

Environment variable required for HTTP mode:
- `AUTOMATION_TOKEN`

Optional query params:
- `cycle_days` (default `30`)
- `grace_days` (default `3`)

What automation does:
1. Creates renewal invoices for active/running services after cycle window.
2. Suspends services when unpaid renewal invoices pass grace window.

Admin audit logs page:
- `/admin/audit-logs`

## Console Troubleshooting Guide
If noVNC console does not open reliably:

1. Open browser DevTools and check if WebSocket request is blocked.
2. Verify SolusVM returns both `vnc_proxy_url` and `url` token in `vnc_up` response.
3. Use `Open Fallback Console URL` button from console page.
4. If reverse proxy is used (Nginx/Cloudflare), ensure WebSocket upgrade headers are enabled.
5. Confirm firewall allows VNC proxy ports from app server.
6. Check mixed-content: do not use `ws://` from an `https://` site.

Nginx WebSocket essentials:

```nginx
proxy_http_version 1.1;
proxy_set_header Upgrade $http_upgrade;
proxy_set_header Connection "upgrade";
proxy_read_timeout 3600;
```

## Go-To-Market Next Steps
1. Replace SQLite with MySQL/PostgreSQL and add backups.
2. Add payment gateway (Stripe/PayPal) with webhook verification.
3. Add email notifications (invoice, suspension, ticket updates).
4. Add role-based support workflow (open, in_progress, resolved, closed).
5. Set up CI/CD (lint, tests, deployment pipeline).
6. Add SLA/support policy pages and legal docs (TOS, Privacy, Refund).
