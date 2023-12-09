# Juego Multiusuario en Laravel

Esta es una aplicación Laravel para un juego multiusuario con las siguientes rutas:

## Rutas del Juego

- **Entrar al Juego:**
  - Método: `POST`
  - URL: `http://localhost:8000/game/entrar`
  - Descripción: Ingresa al juego.
  - Datos requeridos:
    ```json
    {"name": "example"}
    ```

- **Iniciar Juego:**
  - Método: `POST`
  - URL: `http://localhost:8000/game/iniciar`
  - Descripción: Inicia el juego.
  - Datos requeridos:
    ```json
    {"name": "example", "letter": "a"}
    ```

- **Generar Palabra Aleatoria:**
  - Método: `GET`
  - URL: `http://localhost:8000/game/generar`
  - Descripción: Genera una palabra aleatoria para el juego.

- **Listar Usuarios:**
  - Método: `GET`
  - URL: `http://localhost:8000/game/usuarios`
  - Descripción: Obtiene la lista de usuarios en el juego.

- **Limpiar Cache:**
  - Método: `DELETE`
  - URL: `http://localhost:8000/game/cache`
  - Descripción: Borra la caché del juego.

## Pasos del Juego

1. **Entrar al Juego:**
   - Envía una solicitud POST a `http://localhost:8000/game/entrar` con el siguiente formato:
     ```json
     {"name": "jugador1"}
     ```
  - Se puede ingresar un maximo de 3 jugadores y se asignan los turnos secuencialmente
2. **Entrar al Juego (Jugador 2):**
   - Repite el paso 1 para dos jugadores más, utilizando nombres diferentes, por ejemplo:
     ```json
     {"name": "jugador2"}
     {"name": "jugador3"}
     ```

3. **Generar Palabra:**
   - Genera una palabra aleatoria utilizando la ruta `http://localhost:8000/game/generar`.

4. **Listar Usuarios:**
   - Obtiene la lista de usuarios en el juego haciendo una solicitud GET a `http://localhost:8000/game/usuarios`.

5. **Iniciar Juego:**
   - Envía una solicitud POST a `http://localhost:8000/game/iniciar` con el siguiente formato:
     ```json
     {"name": "jugador1", "letter": "a"}
     ```
  - El juego solo acaba cuando alguien adivina la palabra, no logre hacer funcionar el juego aqui con turnos
6. **Limpiar Cache:**
   - Utiliza la ruta `http://localhost:8000/game/cache` para borrar la caché del juego si es necesario.

Recuerda ajustar la URL y los puertos según tu configuración específica. ¡Diviértete jugando!
