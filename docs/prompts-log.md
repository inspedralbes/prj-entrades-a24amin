# IA Traceability Log - Shôko Cinema

Aquest document detalla el procés de desenvolupament guiat per intel·ligència artificial seguint la metodologia Spec-Driven Development (SDD).

## Iteració 1: Definició i Bases del Temps Real
- **Prompt**: "Vull crear una plataforma de venda d'entrades on el temps real sigui el nucli. Necessito que el servidor gestioni la concurrència i que el client s'actualitzi via Socket.io."
- **Restultat**: Creació de l'arquitectura base (Node.js + Redis + Socket.io + Laravel). Definició dels estats de seient.
- **Correcció**: Es va detectar que el client intentava canviar l'estat localment; es va corregir forçant que el servidor sigui l'única font de veritat (Source of Truth).

## Iteració 2: Millora de la Visualització del Dashboard
- **Prompt**: "El gràfic d'ocupació no es veu bé amb noms llargs. Enumerem les pel·lis de l'1 al 30 i posem una llegenda a la taula."
- **Restultat**: Implementació d'índexs numèrics als gràfics i taules de l'admin.
- **Error detectat**: Els números no coincidien entre la taula i el gràfic en cas de filtratge. Es va solucionar usant l'índex real de l'array de dades.

## Iteració 3: Procés de Pagament i UX
- **Prompt**: "Fem que el pagament sembli real. Afegeix un formulari de targeta amb auto-formatat (espais cada 4 xifres i barra a la data)."
- **Restultat**: Implementació de watchers en Vue per al formatat de la targeta i CVV. Centrat del botó de compra i correcció d'overflow del contenidor.
- **Relació amb Spec**: Es va mantenir la coherència amb el Spec de "Procés de compra" que exigia validació de dades personals.

## Reflexió sobre l'Agent d'IA
L'agent ha seguit amb precisió les especificacions definides als fitxers MarkDown. La interpretació de la concurrència (conflict resolution) ha estat consistent, movent tota la validació crítica al servidor Node/Redis. S'han requerit aproximadament 3-4 iteracions per polir detalls visuals, però la lògica de negoci base ha estat sòlida des de la primera aplicació.
