# Desplegament de l'aplicació

Aquesta aplicació consta de dues parts: un **backend en Symfony (PHP)** i un **frontend en Vue.js**. A continuació s'expliquen els passos per executar-la en local o amb Docker.

---

## Requeriments previs

Per al desplegament en local:

- PHP (recomanat 8.1 o superior)
- Symfony CLI
- Composer
- Node.js i npm
- MySQL o MariaDB
- Git (opcional)

Per al desplegament amb Docker:

- Docker
- Docker Compose

---

## Mètode 1: Desplegament en local

### Configuració del backend (Symfony)

1. Clona el projecte si no ho has fet:

   ```bash
   git clone https://exemple.com/repositori.git
   cd granota-backend
   ```

2. Crea una base de dades MySQL buida.

3. Modifica la cadena de connexió de la base de dades al fitxer `.env.local`:

   ```env
   DATABASE_URL="mysql://usuari:contrasenya@127.0.0.1:3306/nom_de_la_base_de_dades"
   ```

4. Des del directori del backend, executa els següents comandaments:

   ```bash
   symfony server:start -d               # Inicia el servidor en segon pla
   symfony console doctrine:database:create
   symfony console doctrine:migrations:migrate
   symfony console app:create-admin     # Crea un usuari administrador
   symfony console app:import-species ./species.csv
   ```

   > Assegura't que el fitxer `species.csv` es troba a l'arrel del directori del backend.

### Configuració del frontend (Vue.js)

1. Accedeix al directori del frontend:

   ```bash
   cd granota
   ```

2. Instal·la les dependències:

   ```bash
   npm install
   ```

3. Executa el servidor de desenvolupament:

   ```bash
   npm run serve
   ```

   Per defecte, l’aplicació s’executarà a [http://localhost:8080](http://localhost:8080).

---

## Mètode 2: Desplegament amb Docker

1. Accedeix al directori `docker`:

   ```bash
   cd docker
   ```

2. Executa el següent comandament per iniciar els contenidors:

   ```bash
   docker-compose up -d
   ```

   Això crearà i iniciarà els serveis de backend, frontend i base de dades en contenidors.

---

## Accés

- **Frontend**: https://localhost:80  
---


## Desenvolupat amb

- Symfony (PHP)
- Vue.js
- MySQL
