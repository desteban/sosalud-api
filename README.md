<p align="center"><a href="https://sosalud.com.co" target="_blank"><img src="https://sosalud.com.co/wp-content/uploads/2020/11/Logo.png" width="400"></a></p>

# Sistema de información auditoría de cuentas médicas y concurrente

Este **módulo** permite realizar **validaciones** a las **cuentas médicas** generadas en clínicas y hospitales al momento de realizar procedimientos médicos a un paciente, estas validaciones **detectan errores** dentro de estas cuentas médicas los cuales se le **notifica** a las entidades que remiten estas cuenta médica y de esta manera puedan corregir los errores antes de que un auditor valide la información de estas cuentas médicas.

---

<br/>

## Restricciones

-   <p style="display:flex;" ><img src="https://laravel.com/img/logomark.min.svg" height="25" style="margin-right:8px" > Laravel 9</p>
-   <p style="display:flex;" ><img src="https://cdn.svgporn.com/logos/php.svg" height="25" style="margin-right:8px" > PHP ^8.0.2</p>
-   <p style="display:flex;" ><img src="https://cdn.svgporn.com/logos/mysql-icon.svg" height="25" style="margin-right:8px" > Gestor de base de datos MySQL</p>
-   <p style="display:flex;" ><img src="https://cdn.svgporn.com/logos/apache.svg" width="40" height="25" style="margin-right:8px"  > Servidor Apache</p>
-   <p style="display:flex;" ><img src="https://cdn.svgporn.com/logos/composer.svg" width="40" height="30" style="margin-right:8px"  >Composer</p>

---

<br/>

## Instalación

-   <a href="https://github.com/desteban/sosalud" target="_blanck" >Clonar o descargar el proyecto</a>
-   Instalar los paquetes y dependencias requeridos

    ```
    composer install
    ```

-   **Crear** el archivo `.env`, para esto puedes crearlo manualmente o copiar el de ejemplo el cual viene con el proyecto de esta manera

    ```
    cp .env.example .env
    ```

-   Generar **APP_KEY**

    ```
    php artisan key:generate
    ```

-   **Configurar** las variables de entorno dentro del archivo **.env**

-   Dar **permisos** a los **directorios (linux)**

    -   Permiso a la carpeta del proyecto
        ```
        sudo chmod 775 -R .
        ```
    -   Carpeta de archivos temporales

        Utilizaremos el siguiente comando para cambiar los permisos por defecto del directorio

        ```
        sudo setfacl --default --modify u::rwx,g::rwx,o::rwx <directorio>
        ```

        Donde:

        `r`: dar permiso de **lectura**

        `w`: dar permiso de **escritura**

        `x`: dar permiso de **ejecución**

        ```
        sudo setfacl --default --modify u::rwx,g::rwx,o::rwx public/TMPs/

        sudo setfacl --default --modify u::rwx,g::rwx,o::rwx storage/app/comprimidos/
        ```
