# Guia de Desplegament Pas a Pas: 89.167.59.195

Aquesta guia conté els passos exactes per pujar el teu projecte **Shôko Cinema** a la màquina del teu institut.

## 1. Connexió al Servidor
Obre el teu terminal i connecta't per SSH:
```bash
ssh root@89.167.59.195
```
Quan et demani la contrasenya, posa: `pHpvrfFELtbWfcnkigrs` (no es veurà mentre escrius, és normal).

## 2. Instal·lar Docker (Si no està instal·lat)
Un cop dins del servidor, executa aquestes comandes:
```bash
# Actualitzar paquets
apt update && apt upgrade -y

# Instal·lar Docker
apt install docker.io docker-compose -y
```

## 3. Preparar el Repositori a la Màquina
Clona el teu projecte a la carpeta de producció:
```bash
cd /root
git clone https://github.com/EL-TEU-USUARI/prj-entrades-a24amin.git
cd prj-entrades-a24amin
```

## 4. Configuració de Producció (.env)
Has de crear els fitxers de configuració per a producció.

### Backend:
```bash
cp backend/.env.example backend/.env
```
Edita'l amb `nano backend/.env` i canvia:
- `APP_URL=http://89.167.59.195`
- `DB_HOST=mysql`
- `DB_PASSWORD=password`
- `REDIS_HOST=redis`

### Frontend:
Edita el fitxer `frontend/.env` (o crea'l) i posa'hi:
- `NUXT_PUBLIC_API_BASE=http://89.167.59.195:8000/api`
- `SOCKET_URL=http://89.167.59.195:3000`

## 5. Engegar el Projecte
```bash
docker-compose -f docker-compose.prod.yml up -d --build
```

## 6. Inicialitzar la Base de Dades
Executa les migracions i el seeder a dins del contenidor de producció:
```bash
docker exec entrades_backend php artisan migrate --force
docker exec entrades_backend php artisan db:seed --class=MassiveEventSeeder --force
```

## 7. Automatització amb GitHub Actions (Molt Recomanat)
Ves als **Settings** del teu repositori a GitHub i afegeix aquests `Secrets`:
- `SERVER_HOST`: `89.167.59.195`
- `SERVER_USER`: `root`
- `SSH_PRIVATE_KEY`: La teva clau privada RSA (la que generis amb `ssh-keygen`).
- `SERVER_PASSWORD`: `pHpvrfFELtbWfcnkigrs`

Amb el fitxer `.github/workflows/deploy.yml` que t'he creat, cada cop que facis un `push`, el servidor s'actualitzarà sol.

---
**IMPORTANTE**: Si vols que funcioni amb **HTTPS**, hauràs de comprar un domini i configurar Nginx amb Certbot, però per a la presentació del projecte, l'accés per IP (`http://89.167.59.195`) sol ser suficient.
