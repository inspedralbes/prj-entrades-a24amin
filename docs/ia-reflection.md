# Reflexió Final - Desenvolupament amb IA (SDD)

## L'agent ha seguit realment la especificació?
Sí. El procés va començar amb la definició de les "restriccions" a `foundations.md`. L'IA ha respectat que el servidor sigui l'única font de veritat, evitant validacions febles al frontend. El flux definit a `spec.md` (reserva -> timer -> checkout) s'ha implementat sense desviacions.

## Quantes iteracions han estat necessàries?
Per a la funcionalitat de reserva en temps real, van caldre **3 iteracions principals**:
1. Establiment dels contractes Socket.io.
2. Gestió de l'estat a Pinia.
3. Resolució de concurrència (quan dos usuaris trien el mateix seient).

## On falla més la IA?
- **Interpretació**: A vegades intenta simplificar massa les rutes API (ex: no passar l'ID de l'esdeveniment i assumir-lo).
- **Execució**: En el disseny visual (CSS), pot deixar elements amb desbordaments si no se li especifica el `box-sizing` o el `z-index`.
- **Coherència**: És molt bona mantenint la coherència entre el Backend i el Frontend si se li dona el Spec complet des del principi.

## Conclusions
La metodologia SDD permet estalviar molt de temps en "bug fixing" estructural. Definir els diagrames i les regles abans de generar codi asegura que l'IA no inventi comportaments inesperats durant la concurrència d'alta demanda.
