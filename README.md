# Projecte Platforma de Venda d’Entrades en Temps Real

**Desenvolupador del projecte:** Amin  
**Objectiu “breu” del projecte:** Crear una plataforma concurrent de venda d'entrades per a alta demanda, utilitzant tecnologia temps real amb clars sistemes de bloqueig temporal, on el client compateix pels seients competint amb altres clients.  
**Estat “breu” del projecte:** Inicialització del sistema, preparació Docker i definició arquitectònica i de protocol de dades. Sense codi.  
**Adreça web de la documentació phpdoc:** daw.inspedralbes.cat  
**Adreça web del projecte desplegat:** nomBotiga.daw.inspedralbes.cat  
**Carpeta pels CSS, img, i JS:**  
- `frontend/public/` (Nuxt)
- `backend/public/` (Laravel)

**Enllaç a l’eina de prototipatge:** *(A omplir més endavant quan tinguis el Penpot/Figma).*

## Estructura de carpetes principal

- `backend/`: Contindrà l'aplicació PHP principal (sistema API, validacions, connexió primària BD).
- `frontend/`: Aplicació client amb Nuxt 3, Vue, compostos i Pinia.
- `websocket-server/`: Microservei Node.js dedicat només al sistema Socket.IO per rebre i enviar les actualitzacions Reactives i en xarxa de l'esdeveniment a tothom alhora.
- `database/`: Scripts per disseny i poblament inicial de la base de dades.
- `docs/`: Diagrames i altres documents importants.
- `specs/`: Definicions OpenSpec exigides per treballar el SDD amb la IA.
