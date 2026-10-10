# 🏟️ Guía de Instalación Local — Cosmol-Deportes

> Requisito: **XAMPP** con **PHP 7.3** (Apache + MySQL/MariaDB).

---

## Paso 1: Instalar XAMPP con PHP 7.3

Si aún no lo tienes instalado:

1. Descargar **XAMPP 7.3.x** desde [https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/).

   > [!IMPORTANT]
   > Debes descargar una versión de XAMPP que incluya **PHP 7.3.x** (ejemplo: `xampp-windows-x64-7.3.33-1-VC15-installer.exe`). **No uses** versiones con PHP 7.4, 8.0, 8.1 o posteriores.

2. Instalar en la ruta por defecto `C:\xampp` (o la que prefieras).

---

## Paso 2: Colocar el Proyecto en la Carpeta de Apache

Hay **dos opciones** para que Apache sirva el proyecto:

### Opción A — Symlink / Alias (Recomendada)

Crear un **alias de Apache** para no mover tu carpeta de proyecto.

1. Abrir el archivo `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2. Agregar al final:

```apache
<VirtualHost *:80>
    DocumentRoot "c:/Proyectos/cosmol-deportes/public"
    ServerName cosmol.local

    <Directory "c:/Proyectos/cosmol-deportes/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. Editar el archivo de hosts de Windows (`C:\Windows\System32\drivers\etc\hosts`) **como Administrador** y agregar:

```
127.0.0.1   cosmol.local
```

4. Asegúrate de que en `C:\xampp\apache\conf\httpd.conf` estén habilitados:
   - `LoadModule rewrite_module modules/mod_rewrite.so` (sin `#` al inicio)
   - `Include conf/extra/httpd-vhosts.conf` (sin `#` al inicio)

### Opción B — Copiar a htdocs (Más sencilla)

Copiar o crear un symlink del proyecto dentro de `C:\xampp\htdocs\`:

```powershell
# Crear enlace simbólico (ejecutar PowerShell como Administrador)
New-Item -ItemType SymbolicLink -Path "C:\xampp\htdocs\cosmol-deportes" -Target "c:\Proyectos\cosmol-deportes"
```

Con esta opción, la URL de acceso será: `http://localhost/cosmol-deportes/`

---

## Paso 3: Iniciar los Servicios de XAMPP

1. Abrir el **Panel de Control de XAMPP** (`C:\xampp\xampp-control.exe`).
2. Hacer clic en **Start** en los módulos:
   - ✅ **Apache**
   - ✅ **MySQL**
3. Verificar que ambos queden en **verde** (sin errores de puerto).

> [!TIP]
> Si el puerto 80 está ocupado (por ejemplo, por Skype o IIS), puedes cambiar el puerto de Apache en `C:\xampp\apache\conf\httpd.conf` buscando `Listen 80` y cambiándolo a `Listen 8080`.

---

## Paso 4: Crear la Base de Datos

### 4.1 — Abrir phpMyAdmin

Ir a [http://localhost/phpmyadmin](http://localhost/phpmyadmin) en tu navegador.

### 4.2 — Crear la base de datos

1. Clic en **"Nueva"** (panel izquierdo) o pestaña **"Bases de datos"**.
2. Nombre: **`cosmol_deportes`**
3. Cotejamiento: **`utf8mb4_unicode_ci`**
4. Clic en **Crear**.

### 4.3 — Importar el esquema

1. Seleccionar la base `cosmol_deportes` en el panel izquierdo.
2. Ir a la pestaña **"Importar"**.
3. Clic en **"Seleccionar archivo"** → navegar a:
   ```
   c:\Proyectos\cosmol-deportes\database\schema.sql
   ```
4. Clic en **"Continuar"** / **"Go"**.

### 4.4 — Importar datos semilla

Repetir el proceso de importación con:
```
c:\Proyectos\cosmol-deportes\database\seeds.sql
```

> [!NOTE]
> El archivo `seeds.sql` contiene el usuario administrador por defecto, las disciplinas base (Fútbol, Futsal, Básquetbol, Voleibol) y sus reglas de puntuación.

---

## Paso 5: Configurar el Archivo `.env`

El proyecto ya tiene un archivo [`.env`](file:///c:/Proyectos/cosmol-deportes/.env) con valores por defecto para XAMPP. Verificar que los valores coincidan con tu entorno:

```env
# Configuración de Base de Datos para XAMPP
DB_CONNECTION="mysql"
DB_HOST="localhost"
DB_PORT=3306
DB_DATABASE="cosmol_deportes"
DB_USERNAME="root"
DB_PASSWORD=""
DB_CHARSET="utf8mb4"
```

> [!WARNING]
> Si tu MySQL de XAMPP tiene contraseña para `root`, actualiza `DB_PASSWORD` con la contraseña correspondiente.

---

## Paso 6: Verificar `mod_rewrite` de Apache

El proyecto usa reescritura de URLs vía `.htaccess`. Asegurarse de que `mod_rewrite` esté habilitado:

1. Abrir `C:\xampp\apache\conf\httpd.conf`
2. Buscar la línea:
   ```
   #LoadModule rewrite_module modules/mod_rewrite.so
   ```
3. Si tiene `#` al inicio, **quitarlo** para que quede:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
4. También verificar que el bloque `<Directory>` de htdocs tenga `AllowOverride All`:
   ```apache
   <Directory "C:/xampp/htdocs">
       AllowOverride All
       Require all granted
   </Directory>
   ```
5. **Reiniciar Apache** desde el panel de XAMPP.

---

## Paso 7: Acceder al Proyecto

Dependiendo de la opción que elegiste en el Paso 2:

| Método | URL de acceso |
|---|---|
| **Opción A** (VirtualHost) | `http://cosmol.local` |
| **Opción B** (htdocs symlink) | `http://localhost/cosmol-deportes/` |

Deberías ver la pantalla de **Login** del sistema.

---

## Paso 8: Iniciar Sesión

Usar las credenciales del usuario administrador creado por `seeds.sql`. Revisa ese archivo para conocer el email y contraseña por defecto.

---

## Resumen de Versiones Requeridas

| Herramienta | Versión |
|---|---|
| **XAMPP** | 7.3.x |
| **PHP** | 7.3.x (incluido en XAMPP) |
| **Apache** | 2.4.x (incluido en XAMPP) |
| **MySQL / MariaDB** | 5.7 / 10.4 (incluido en XAMPP) |
| **AdminLTE** | 3.x (assets en `public/adminlte/`) |
| **Bootstrap** | 4.x (incluido con AdminLTE) |
| **jQuery** | 3.x (incluido con AdminLTE) |
| **FontAwesome** | 5.x (incluido con AdminLTE) |

---

## Solución de Problemas Comunes

### ❌ "No se pudo conectar a la base de datos"
- Verificar que **MySQL esté corriendo** en XAMPP.
- Verificar que la base `cosmol_deportes` exista en phpMyAdmin.
- Revisar credenciales en `.env`.

### ❌ "404 Not Found" o "The requested URL was not found"
- Verificar que `mod_rewrite` esté habilitado.
- Verificar `AllowOverride All` en la configuración de Apache.
- Reiniciar Apache después de los cambios.

### ❌ "500 Internal Server Error"
- Revisar el log de errores: `C:\xampp\apache\logs\error.log`
- Puede ser un error de sintaxis PHP o un `.htaccess` mal configurado.
