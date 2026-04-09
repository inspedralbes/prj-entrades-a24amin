# Shôko Cinema - Plataforma de Venda d'Entrades en Temps Real

**Desenvolupador del projecte:** Amin  
**Objectiu “breu” del projecte:** Crear una plataforma concurrent de venda d'entrades per a alta demanda amb Socket.IO, Laravel i Nuxt 3.  
**Estat “breu” del projecte:** Finalització de la Fase 9. Sistema complet amb reserva en temps real, pagament, panell d'administració amb analítica i tests de concurrència.  
**Adreça web de la documentació phpdoc:** [daw.inspedralbes.cat](https://daw.inspedralbes.cat)  
**Adreça web del projecte desplegat:** [shokocinema.daw.inspedralbes.cat](http://shokocinema.daw.inspedralbes.cat)  
**Carpeta pels CSS, img, i JS:**  
- `frontend/app/assets/`
- `frontend/app/public/`
- `backend/public/`

**Enllaç a l’eina de prototipatge:** [Figma/Penpot Shôko Cinema](https://www.figma.com/file/...)


## Estructura de carpetes principal

- `backend/`: Contindrà l'aplicació PHP principal (sistema API, validacions, connexió primària BD).
- `frontend/`: Aplicació client amb Nuxt 3, Vue, compostos i Pinia.
- `websocket-server/`: Microservei Node.js dedicat només al sistema Socket.IO per rebre i enviar les actualitzacions Reactives i en xarxa de l'esdeveniment a tothom alhora.
- `database/`: Scripts per disseny i poblament inicial de la base de dades.
- `docs/`: Diagrames i altres documents importants.
- `specs/`: Definicions OpenSpec exigides per treballar el SDD amb la IA.
