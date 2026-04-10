# Guia de Desplegament a Producció - Shôko Cinema

Aquesta guia detalla els passos per passar l'aplicació del teu entorn local a un servidor real amb Docker, HTTPS i GitHub Actions.

## 1. Preparació del Servidor (Màquina)

Un cop tinguis la teva màquina (Ubuntu/Debian recomanat), connecta't per SSH i instal·la el necessari:

```bash
# Actualitzar sistema
sudo apt update && sudo apt upgrade -y

# Instal·lar Docker i Docker Compose
sudo apt install docker.io docker-compose -y
sudo usermod -aG docker $USER # Reinicia la sessió després
```

## 2. Configuració del Domini i HTTPS

Perquè el projecte sigui accessible via `https://el-teu-domini.com`, necessitem un **Proxy Invers** (Nginx):

1. **Instal·la Nginx**: `sudo apt install nginx -y`
2. **Instal·la Certbot**: `sudo apt install certbot python3-certbot-nginx -y`
3. **Crea un certificat**: `sudo certbot --nginx -d el-teu-domini.com`

## 3. Automatització amb GitHub Actions

Aquest és el mètode més professional. Cada cop que facis `git push`, el servidor s'actualitzarà sol.

### Pas A: Secrets de GitHub
Ves al teu repositori a GitHub -> **Settings** -> **Secrets and variables** -> **Actions** i afegeix:
- `SERVER_HOST`: La IP de la teva màquina.
- `SERVER_USER`: El teu usuari (ex: `ubuntu` o `root`).
- `SSH_PRIVATE_KEY`: La teva clau privada RSA per connectar-te sense contrasenya.

### Pas B: El flux de treball (.github/workflows/deploy.yml)
Crea aquest fitxer al teu projecte (ja te'l deixo preparat al repositori).

## 4. Fitxers .env de Producció

Al servidor, hauràs de crear els fitxers `.env` manuals amb dades reals:
- **Backend**: Canvia `APP_DEBUG=false`, `APP_ENV=production` i posa una `APP_KEY` segura.
- **Frontend**: `NUXT_PUBLIC_API_BASE` ha de ser la URL del teu domini.

## 5. Comandes útils al servidor

Per veure que tot funciona:
- `docker ps`: Mira si els contenidors estan vius.
- `docker logs entrades_backend`: Mira errors de Laravel.
- `docker exec entrades_backend php artisan migrate --force`: Executa les migracions a producció.

---

> [!IMPORTANT]
> Recorda obrir els ports **80** (HTTP), **443** (HTTPS) i **22** (SSH) al firewall del teu proveïdor de cloud.
