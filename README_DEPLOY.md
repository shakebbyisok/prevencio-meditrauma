# 🚀 Despliegue Rápido - Windows Server

## ✅ Estado: LISTO PARA DESPLEGAR

---

## 📋 Pasos en Windows Server:

### 1. Preparar Estructura de Carpetas

Tu estructura actual está bien, pero necesitas:

```
Prevencio/
├── db/                    (o BBDDs/ - ambos funcionan)
│   ├── dump-prevencion-202511120956/
│   │   └── *.sql         (archivos SQL descomprimidos)
│   ├── dump-stats_meditrauma-202511121025/
│   │   └── *.sql
│   └── dump-openqueue-202511121025/
│       └── *.sql
└── [aquí clonarás el repo]
```

**⚠️ IMPORTANTE:** Los archivos `.sql.gz` deben estar **descomprimidos** (`.sql`)

### 2. Clonar Repositorio

```cmd
cd C:\ruta\a\Prevencio
git clone https://github.com/shakebbyisok/prevencio-meditrauma.git .
```

O si prefieres mantener el nombre:
```cmd
git clone https://github.com/shakebbyisok/prevencio-meditrauma.git prevencio-app
```

### 3. Instalar Docker Desktop

- Descargar: https://www.docker.com/products/docker-desktop/
- Instalar y reiniciar

### 4. Iniciar PostgreSQL

```cmd
cd Prevencio
docker-compose up -d
```

Esperar 10-15 segundos para que PostgreSQL inicie.

### 5. Restaurar Bases de Datos

```cmd
restore-db.bat
```

Este script buscará los archivos `.sql` en las carpetas `db/` o `BBDDs/`.

### 6. Configurar Aplicación

```cmd
cd current
copy .env.dist .env
```

Editar `.env` y configurar:
```env
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=generar_uno_nuevo_aqui

DATABASE_URL=postgresql://postgres:postgres123@localhost:5432/prevencion?serverVersion=13&charset=utf8
```

### 7. Instalar Dependencias

```cmd
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod
```

### 8. Configurar IIS

- Abrir IIS Manager
- Crear nuevo sitio web
- **Physical path:** `C:\ruta\a\Prevencio\current\public`
- **Binding:** Puerto 80
- El archivo `web.config` ya está incluido

---

## 📁 Sobre los Archivos de Base de Datos

Veo que tienes:
- **Carpetas** con los nombres de los dumps (✓ está bien)
- **Archivos .sql.gz** comprimidos (⚠️ necesitas descomprimirlos)

**Opciones:**

**Opción A:** Descomprimir los `.sql.gz` dentro de sus carpetas respectivas
- Usar 7-Zip o WinRAR para descomprimir
- Dejar los `.sql` dentro de cada carpeta

**Opción B:** Si las carpetas ya tienen los `.sql` dentro, está perfecto ✅

El script `restore-db.bat` buscará automáticamente los `.sql` en:
- `BBDDs\dump-prevencion-202511120956\*.sql`
- `db\dump-prevencion-202511120956\*.sql` (también funciona)

---

## ⚡ Comandos Útiles:

```cmd
# Ver estado de Docker
docker ps

# Ver logs de PostgreSQL
docker logs prevencio_postgres

# Detener PostgreSQL
docker-compose down

# Reiniciar PostgreSQL
docker-compose restart
```

---

## 🔐 Credenciales por Defecto:

- **Usuario:** postgres
- **Contraseña:** postgres123
- **Base de datos:** prevencion
- **Puerto:** 5432

**⚠️ Cambiar contraseña en producción!**

---

## ✅ Checklist Final:

- [ ] Docker Desktop instalado
- [ ] Repositorio clonado
- [ ] Archivos `.sql` descomprimidos en carpetas `db/`
- [ ] `docker-compose up -d` ejecutado
- [ ] `restore-db.bat` ejecutado sin errores
- [ ] Archivo `.env` configurado
- [ ] `composer install` ejecutado
- [ ] IIS configurado
- [ ] Aplicación accesible en navegador
