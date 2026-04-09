# Documentació del Projecte - Shôko Cinema

Aquesta carpeta conté tota la documentació requerida per a l'entrega final del projecte.

## Llistat de Documents

| Document | Descripció |
| :--- | :--- |
| [**Resum.md**](Resum.md) | Resum Executiu (Abstract) del projecte. |
| [**manual.md**](manual.md) | Manual d'instal·lació i configuració. |
| [**ER-diagram.md**](ER-diagram.md) | Diagrama Entitat-Relació de la base de dades. |
| [**sequence-diagram.md**](sequence-diagram.md) | Diagrama de seqüència del procés de reserva real-time. |
| [**socket-protocol.md**](socket-protocol.md) | Especificació dels events de Socket.IO. |
| [**prompts-log.md**](prompts-log.md) | Traçabilitat de la IA i registre de prompts (SDD). |
| [**video-script.md**](video-script.md) | Guió per al vídeo demo de 90 segons. |

## Mètrica de Qualitat i Requeriments
- **Concurrència**: Implementada al servidor Node.js. Verificació disponible a `frontend/app/cypress/e2e/concurrency.cy.ts`.
- **Temps Real**: Socket.IO integrat amb Pinia per a actualitzacions d'estat immediates.
- **Admin**: Dashboard amb analítica de vendes i ocupació (ChartJS).

