# Sito web per l'Istituto Storico di Forlì-Cesena

Progetto realizzato per tirocinio curriculare universitario in Tecnologie Web (A.A. 2024/2025)  
Sviluppatore: Marco Giugliani

---

## Obiettivo del progetto

Ristrutturazione del backend e del frontend dell'attuale sito web dell'Istituto Storico, con l'obiettivo di uniformare l'esperienza degli utenti e di semplificare la gestione dei contenuti da parte degli amministratori, tramite l'accorpamento di tutti i siti affiliati all'Istituto.

---

## Deployment tramite Docker

N.B. È necessario avere installato l'applicazione Docker Desktop per poter proseguire.

git clone https://github.com/Giulius03/IstitutoStorico.git 
cd percorso/cartella/progetto  
docker compose up

---

## Funzionalità previste

Amministrazione:
- Possibilità di aggiungere, modificare ed eliminare i contenuti presenti nel sito web:
  - Pagine --> Archivi, Raccolte di Risorse, Indici e Note
  - Menù --> Singole Voci
  - Tag
  - Articoli d'inventario
  - Strumenti di corredo
- Possibilità di invio di messaggi tramite email agli utenti iscritti alla newsletter

Utenza normale:
- Visualizzazione dei contenuti pubblicati dall'amministrazione
- Iscrizione alla newsletter

---

## Configurazioni presenti per future modifiche

Le variabili d'ambiente utilizzate all'interno del progetto si trovano all'interno del file *docker-compose.yaml*. Nello specifico sono presenti variabili che indicano:
- Le informazioni riguardanti la connessione al database (host, user, password e nome del db)
- L'indirizzo email e la password SMTP utilizzate per l'invio dei messaggi agli iscritti alla newsletter

---

## Possibili migliorie da effettuare

TODO: Inserimento di un'immagine decorativa per ogni pagina presente, come nel sito attuale dell'Istituto (opzionale)  
