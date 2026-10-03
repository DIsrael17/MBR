# Autenticación

> Estado: **APROBADO (2026-10-03)** — decisiones en [decisions.md](decisions.md).

## Web
- Starter kit oficial de Laravel (Livewire) sobre **Fortify**: registro, login, logout, recuperación de contraseña, verificación de email, cambio de contraseña, perfil con foto (avatar en storage público, 2 MB máx., recorte cuadrado).
- Sesiones en Redis (prod) / database (local). Cookies `secure`, `http_only`, `same_site=lax`.
- Rutas protegidas con `auth` y `verified` donde se requiera (publicar, favoritos, panel).
- Usuarios bloqueados (`is_blocked`) son rechazados en login y sus sesiones activas invalidadas.
- Registro con checkbox de aceptación de términos y aviso de privacidad (Q-17).
- Registro como agente: el usuario se registra y solicita el rol de agente desde su perfil (formulario con datos profesionales); un admin lo aprueba (BR-AGT-04). Propuesta a confirmar.

## API (`/api/v1`)
- **Sanctum** con tokens personales: `POST /api/v1/auth/register`, `/login` (devuelve token, nombre de dispositivo), `/logout` (revoca token actual), `GET /api/v1/auth/me`, `/forgot-password`, `/reset-password`, `/email/verification-notification`.
- Abilities de token: `*` para apps propias en MVP.

## Rate limiting
| Limiter | Regla |
|---|---|
| login | 5 intentos/min por email+IP |
| register | 10/hora por IP |
| password-reset | 3/hora por email |
| api | 60/min por usuario o IP |

## Post-MVP
Login social (Google, Facebook, Apple) con Socialite + tabla `social_accounts`; 2FA opcional (Fortify lo soporta).
