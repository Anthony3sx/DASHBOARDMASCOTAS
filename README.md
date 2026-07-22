# Santuario de Mascotas - POO

Proyecto PHP/MySQL que integra la guía de Programación Orientada a Objetos y el instructivo del Dashboard Administrativo Veterinario con W3.CSS.

## Instalación en XAMPP

1. Copia la carpeta `santuario-mascotas` dentro de `C:\\xampp\\htdocs`.
2. Inicia Apache y MySQL desde XAMPP.
3. Abre `http://localhost/phpmyadmin`.
4. En **Importar**, selecciona `database/santuario_mascotas.sql`.
5. Visita `http://localhost/santuario-mascotas/`.

Si ya instalaste la primera versión, no vuelvas a importar la base completa: importa solamente `database/migracion_dashboard.sql` para agregar DNI y observaciones sin borrar registros.

La configuración predeterminada utiliza usuario `root` sin contraseña. Si tu MySQL usa otros datos, edita `config/config.php`.

## POO aplicada

- `Mascota`: 8 propiedades protegidas, constructor, getters, setters y validación.
- `Conexion`: conexión PDO segura con excepciones.
- `MascotaRepositorio extends Conexion`: herencia y guardado con consulta preparada.
- `limpiarEntrada()`: elimina espacios, barras invertidas y etiquetas HTML.
- `procesar.php`: ejecuta el flujo completo y controla errores.

## Dashboard solicitado

- Archivo principal exacto: `dashboard_vet.php`.
- Barra lateral con `w3-sidebar` y `w3-bar-block`.
- Menús y submenús con `w3-hover-blue`.
- Secciones con `w3-card`.
- Formulario distribuido con `w3-row-padding`.
- Alergias/Observaciones mediante `w3-textarea`.
- Botones funcionales para guardar y limpiar.
- Diseño adaptable a computadoras, tabletas y teléfonos.
