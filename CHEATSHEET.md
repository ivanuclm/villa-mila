# 🧭 Laravel + Filament v4 Cheatsheet (Villa Mila Project)

Guía de referencia rápida para el desarrollo de tu proyecto con **Laravel**, **Filament v4**, **Docker** y **Sail**.

> 💡 Puedes editar este archivo en cualquier editor Markdown (VS Code, Obsidian, Typora…).  
> Mantén tus notas, comandos y snippets aquí.  

---

## ⚙️ 1. Entorno (Docker + Sail + WSL2)

### 🐧 WSL2
Si no lo tienes, abre PowerShell como administrador y ejecuta:

```powershell
wsl --install
```

Si ya lo tenías, asegúrate de que la distro por defecto es Ubuntu y la versión es 2:
```powershell
wsl --set-default-version 2
```
Abre la app Ubuntu desde el menú Inicio y deja que termine la instalación. Crea usuario y contraseña cuando te lo pida.

Luego, con buscar en el menú de Inicio "WSL" ya podemos abrirlo.

### 🐳 Docker
Es un sistema que permite ejecutar **contenedores**, es decir, entornos ligeros y aislados con todo el software necesario (PHP, MySQL, Redis, etc.) sin instalarlo en tu máquina directamente.

Abrir Docker Desktop para Windows, ve a Settings, Resources > WSL Integration, y marca Enable integration para Ubuntu.

En WSL comprobamos si funciona con:
```bash
docker version
```

### 📂 Instalar Laravel

```bash
cd ~
#Crea el proyecto con servicios incluidos: PostgreSQL, Redis y Mailpit
curl -s https://laravel.build/villa-mila?with=pgsql,redis,mailpit | bash
cd villa-mila
```

Si da error al conectar con la R2 de CloudFlare, le mandáis un saludo a la madre de Javier Tebas Medrano de mi parte y os instaláis la VPN de Cloudflare WARP para volver a intentarlo.

También revisamos que no tenemos ninguna DNS rara
```bash
cat /etc/resolv.conf
```

### ⛵ Sail
Es una **capa de comandos simplificados** que Laravel ofrece para manejar Docker.  
Usarás `./vendor/bin/sail` para ejecutar Artisan, Composer, NPM, etc. dentro del contenedor.

### 💻 VS Code
Como editor de texto del proyecto.

En WSL revisamos si funciona con:
```bash
docker version
```

### 🌐 Comandos básicos
```bash
cd villa-mila                    # Ejecutamos TODO desde la raíz del proyecto
./vendor/bin/sail up -d          # Inicia los contenedores (PHP, MySQL, etc.)
./vendor/bin/sail down           # Detiene los contenedores
./vendor/bin/sail ps             # Muestra los servicios activos
```

### 🔌 Accesos rápidos
- Aplicación: [http://localhost](http://localhost)
- Panel de administración Filament: [http://localhost/admin](http://localhost/admin)
- Mailpit (emails de prueba): [http://localhost:8025](http://localhost:8025)

---

## 🧱 2. Composer, Artisan y NPM

### 📦 Composer
Gestor de dependencias de PHP.  
Ejemplo para instalar un paquete:
```bash
./vendor/bin/sail composer require vendor/paquete
```

### 🛠 Artisan
Interfaz de línea de comandos de Laravel. Permite crear modelos, migraciones, seeders, etc.
```bash
./vendor/bin/sail artisan migrate          # Ejecutar migraciones
./vendor/bin/sail artisan db:seed          # Ejecutar seeders
./vendor/bin/sail artisan optimize:clear   # Limpiar cachés
```

### 🎨 NPM + Vite
Usado para gestionar y compilar los assets (CSS, JS).
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev      # Desarrollo (watch)
./vendor/bin/sail npm run build    # Producción (optimizado)
```

---

## 🧬 3. Migraciones y Modelos

### Crear modelo con migración
```bash
./vendor/bin/sail artisan make:model Nombre -m
```
Esto crea:
- `app/Models/Nombre.php` → define la lógica del modelo.
- `database/migrations/xxxx_create_nombres_table.php` → define la estructura de la tabla con versiones.

### Ejecutar migraciones
```bash
./vendor/bin/sail artisan migrate
```

### Ejemplo de modelo
```php
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}
```

---

## 🌱 4. Seeders y Factories

### 🪴 Seeder
Inserta **datos concretos** en la base de datos (útil para datos reales de ejemplo).
```bash
./vendor/bin/sail artisan make:seeder DemoSeeder
./vendor/bin/sail artisan db:seed --class=DemoSeeder
```

### 🧪 Factory
Genera **datos falsos** automáticamente (útil para pruebas y tests).
```bash
./vendor/bin/sail artisan make:factory BookingFactory --model=Booking
```

#### Diferencias
| Seeder | Factory |
|--------|----------|
| Inserta datos definidos por ti | Genera datos aleatorios |
| Se ejecuta con `db:seed` | Se usa desde seeders o tests |
| Útil para demos y datos reales | Útil para rellenar tablas de prueba |

---

## 🧰 5. Comandos Artisan comunes
```bash
./vendor/bin/sail artisan migrate:fresh --seed   # Limpia la BD y ejecuta migraciones+seeders
./vendor/bin/sail artisan key:generate           # Genera clave de app
./vendor/bin/sail artisan storage:link           # Crea enlace simbólico para subir imágenes
./vendor/bin/sail artisan make:filament-user     # Crea usuario para Filament
```

---

## 🖼 6. Filament v4 (Panel de Administración)

### 🪄 ¿Qué es Filament?
Es un **panel administrativo para Laravel**, modular, moderno y altamente personalizable.  
Permite crear CRUDs (Listar, Crear, Editar, Eliminar) con solo definir **Forms** y **Tables** en código PHP.

### Instalación del panel
```bash
./vendor/bin/sail artisan filament:install --panels
```

### Creación de usuario administrador
```bash
./vendor/bin/sail artisan make:filament-user
```

### Namespaces esenciales
```php
use Filament\Schemas\Schema;                       // contenedor principal de formularios
use Filament\Schemas\Components\Section;           // layout (agrupa campos)
use Filament\Forms\Components\{TextInput,Textarea,Select,DatePicker,TimePicker,Toggle};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\{EditAction, DeleteBulkAction, BulkActionGroup};
```

### Estructura de un Resource en v4
```
app/
└── Filament/
    └── Resources/
        ├── ListingResource.php
        ├── Listings/
        │   ├── Schemas/ListingForm.php
        │   └── Tables/ListingTable.php
```

Cada recurso define:
- **Form** → campos del formulario.
- **Table** → columnas, filtros, acciones.

---

## 📋 7. Ejemplo de tabla Filament v4
```php
->columns([
    TextColumn::make('name')->sortable()->searchable()->label('Nombre'),
])
->recordActions([
    EditAction::make(),
])
->toolbarActions([
    BulkActionGroup::make([
        DeleteBulkAction::make(),
    ]),
]);
```

---

## 📄 8. Ejemplo de formulario Filament v4
```php
return $schema
    ->columns(2)
    ->schema([
        Section::make('Datos básicos')
            ->columns(2)
            ->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Textarea::make('description')->rows(4)->columnSpanFull(),
            ]),
    ]);
```

---

## 💾 9. Limpieza y mantenimiento
```bash
./vendor/bin/sail artisan optimize:clear   # Limpia cachés
./vendor/bin/sail artisan migrate:fresh    # Reinicia BD
./vendor/bin/sail down && ./vendor/bin/sail up -d   # Reinicia contenedores
```

---

## 🧩 10. Extensiones útiles (Spatie)
- **Media Library**: manejo de imágenes y archivos
  ```bash
  ./vendor/bin/sail composer require spatie/laravel-medialibrary:^11
  ./vendor/bin/sail artisan vendor:publish --tag="medialibrary-migrations"
  ./vendor/bin/sail artisan migrate
  ```

- **Permission**: roles y permisos
  ```bash
  ./vendor/bin/sail composer require spatie/laravel-permission:^6
  ./vendor/bin/sail artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
  ./vendor/bin/sail artisan migrate
  ```

---

## 💡 11. Errores comunes y soluciones

| Error | Causa | Solución |
|-------|--------|----------|
| `Class Filament\Tables\Actions\EditAction not found` | Namespace incorrecto | `use Filament\Actions\EditAction;` |
| `Class Filament\Forms\Components\Section not found` | Debe importarse desde `Schemas` | `use Filament\Schemas\Components\Section;` |
| `No default Filament panel is set` | No se instaló el panel | `./vendor/bin/sail artisan filament:install --panels` |
| `storage link already exists` | Enlace previo | `./vendor/bin/sail artisan storage:unlink && ./vendor/bin/sail artisan storage:link` |

---

## 📚 12. Git (versionado)
```bash
git init
git add .
git commit -m "Inicio del proyecto Laravel + Filament"
git branch -M main
```
Crea un `.gitignore` con:
```
/vendor
/node_modules
/public/storage
```

---

## 🔮 13. Fase 2 (cuando llegues)
Añadiremos:
- Modelos `Season` y `PriceRule`
- Servicio `BookingPriceService` para calcular precios
- Acción “Recalcular precio” en Booking
- Más adelante: Galería, import/export iCal, y mapa interactivo con Leaflet

---

## 🧠 14. Conceptos clave para recordar
- **Migraciones** → estructura de tablas.  
- **Modelos** → lógica y relaciones.  
- **Seeders** → datos concretos.  
- **Factories** → datos aleatorios.  
- **Resources Filament** → interfaz administrativa.  
- **Sail** → ejecuta comandos dentro del entorno Docker sin configurarlo tú.  

---

> ✨ Consejo: cada vez que algo “no carga”, ejecuta:
> ```bash
> ./vendor/bin/sail artisan optimize:clear
> ```
> El 80 % de los errores de Laravel se arreglan así.

---

© 2025 — Iván Hernández García de Mora · Proyecto Villa Mila
