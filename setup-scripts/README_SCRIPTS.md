# 📋 Scripts de Deployment - Prevencio Meditrauma

## 📁 Estructura de Scripts

### Scripts Principales

1. **`deploy.bat`** - Script principal de deployment completo
   - Verifica estructura del proyecto
   - Inicia contenedores Docker (MySQL)
   - Copia assets estáticos
   - Configura `.env`
   - Instala dependencias de Composer
   - Limpia cache de Symfony
   - Configura permisos IIS
   - Instala y configura IIS
   - Configura FastCGI timeout
   - Crea archivos de manifiesto para Webpack Encore

2. **`restore-db.bat`** - Restauración de base de datos
   - Restaura base de datos `prevencion` desde dump SQL
   - Restaura base de datos `stats_meditrauma`
   - Restaura base de datos `openqueue`
   - Agrega columnas faltantes en `fos_user` (rol_id, password_mail, etc.)

3. **`create-missing-tables.bat`** - Crear tablas faltantes usando Doctrine
   - Usa `doctrine:schema:update --force` para crear tablas desde entidades PHP
   - Limpia cache después de crear tablas
   - Verifica que la tabla `revision` fue creada

4. **`create-admin-user.bat`** - Crear usuario administrador
   - Crea usuario `admin` con contraseña `admin6291`
   - Usa FOSUserBundle UserManager para generar hash correcto
   - Maneja columnas faltantes automáticamente

## 🔄 Flujo de Deployment Recomendado

### Primera vez (Deployment completo):

```batch
1. deploy.bat              # Configuración completa del servidor
2. restore-db.bat          # Restaurar base de datos desde dump SQL
3. create-missing-tables.bat  # Crear tablas faltantes usando Doctrine
4. create-admin-user.bat   # Crear usuario admin para testing
```

### Actualizaciones posteriores:

```batch
1. git pull                # Actualizar código
2. deploy.bat              # Reconfigurar si es necesario
3. create-missing-tables.bat  # Si hay nuevas entidades
```

## 📝 Notas Importantes

- **`deploy.bat`** requiere permisos de Administrador
- **`restore-db.bat`** busca dumps SQL en `..\BBDDs\` o `BBDDs\`
- **`create-missing-tables.bat`** crea tablas vacías (sin datos)
- Si faltan datos después de crear tablas, restaurar desde dump SQL

## 🗑️ Scripts Eliminados (Funcionalidad Integrada)

- `clear-cache.bat` → Integrado en `deploy.bat`
- `fix-fastcgi-timeout.bat` → Integrado en `deploy.bat`
- `fix-rol-id-column.bat` → Integrado en `restore-db.bat`
- `restore-missing-tables.bat` → Reemplazado por `create-missing-tables.bat`
- `test-password.bat` → Script temporal eliminado
- `test-password.php` → Script temporal eliminado

## 🔧 Requisitos

- Windows Server con IIS
- Docker Desktop
- PHP instalado (C:\php)
- Composer instalado
- Permisos de Administrador

## 📍 Ubicación de Archivos

- **Dumps SQL**: `..\BBDDs\` o `BBDDs\`
- **Aplicación**: `current\`
- **Assets estáticos**: `portal\public\` → copiados a `current\public\`

