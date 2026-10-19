# Portafolio multipágina — HTML/CSS/PHP

Ejercicio de curso: un sitio de portafolio personal con 6 páginas enlazadas
(inicio, sobre mí, pasatiempos, proyectos, contacto, formulario) y un
formulario de contacto con backend en PHP/MySQL.

## Páginas

`index.html` · `sobremi.html` · `hobbies.html` · `proyectos.html` ·
`contacto.html` · `pagina.html` (formulario)

## Cómo verla

Las páginas estáticas se abren directamente en el navegador. El formulario de
`pagina.html` necesita un servidor PHP con MySQL (por ejemplo MAMP):

```bash
# con MAMP corriendo en el puerto 8889
php -S localhost:8000
```

`index.php` guarda los envíos del formulario en una tabla `formulario`, que
crea sola si no existe. Las credenciales en `index.php` (`root`/`root` en
`localhost:8889`) son las predeterminadas de MAMP para desarrollo local;
cámbialas si lo corres contra otra instancia de MySQL.
