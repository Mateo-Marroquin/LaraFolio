# LaraFolio
LaraFolio es una aplicación en donde puedes explorar cualquier perfil de GitHub y visualizar métricas interesantes sobre él, así como un pequeño resumen de su perfil con solo su nombre de usuario.

La aplicación hace uso de la API REST de GitHub para extraer los datos, por lo que se cuenta con **60 peticiones por hora** para usuarios sin token de autenticación configurado (_por medio del .env o Login con GitHub_), mientras que para usuarios autenticados el número de peticiones sube a **5,000 por hora**.

Si deseas configurar un token personal para elevar tu límite de peticiones a 5,000 por favor sigue la siguiente guía: https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens#creating-a-fine-grained-personal-access-token.
Una vez que tengas tu token, colócalo como valor para el atributo `GITHUB_TOKEN` en las credenciales del archivo .env.

### **Importante**
El sistema implementa el flujo de autenticación OAuth proporcionado por GitHub para acceder a las estadísticas del perfil de forma segura. En un entorno de producción real, de esta configuración se encarga el desarrollador; sin embargo, debido a que esta aplicación está configurada para ejecutarse localmente (localhost), es necesario que registres tu propia aplicación OAuth en tu cuenta de GitHub para que el inicio de sesión funcione en tu máquina.

Para hacerlo, sigue esta guía oficial: https://docs.github.com/en/apps/oauth-apps/building-oauth-apps/creating-an-oauth-app. Una vez generadas tus credenciales, colócalas en los atributos `GITHUB_CLIENT_ID` y `GITHUB_CLIENT_SECRET` dentro de tu archivo .env.
## Características
- **Análisis de Perfiles:** Consulta rápida de lenguajes más utilizados, estadísticas globales y repositorios públicos usando cualquier nombre de usuario de GitHub.
- **Gráficas Reactivas e Interactivas:** Visualización dinámica de métricas y estadísticas del perfil, renderizadas tanto en la interfaz web como en los reportes exportados.
- **Autenticación con GitHub (OAuth):** Inicio de sesión seguro para expandir de inmediato el límite de consultas a la API y acceder a datos extendidos del perfil.
- **Exportación a PDF:** Generación y descarga automática de reportes con el resumen estructurado de las métricas del usuario analizado.
- **Entorno de Pruebas de Correo:** Integración con un servidor local (Mailpit) para la captura, inspección y pruebas de los correos electrónicos generados por el sistema.
## Configuración del entorno
Ya que la aplicación se encuentra construida sobre Laravel Sail con Docker, necesitas ejecutar los siguientes comandos para inicializar y levantar la aplicación por primera vez de forma local:

```bash
# Levantar los contenedores de Docker en segundo plano
./vendor/bin/sail up -d

# Instalar las dependencias de PHP y JavaScript
./vendor/bin/sail composer install
./vendor/bin/sail npm install

# Generar la clave única de la aplicación (.env)
./vendor/bin/sail artisan key:generate

# Ejecutar las migraciones para preparar la base de datos local
./vendor/bin/sail artisan migrate

# Compilar los recursos del frontend con Vite
./vendor/bin/sail npm run dev
```
Una vez levantado el servidor, puedes interactuar con el ecosistema en las siguientes direcciones de tu navegador:

- Aplicación Web: Disponible localmente en http://localhost
- Servidor de Correos (Mailpit): Interfaz para consultar de forma gráfica todos los correos enviados localmente en http://localhost:8025
