# 🔐 Proyecto de Criptografía Clásica en PHP

Este repositorio agrupa una serie de algoritmos de cifrado y descifrado clásicos, con una interfaz web modular y estilizada usando Tailwind CSS. Está pensado para trabajo colaborativo, de modo que cada módulo sea independiente y fácilmente escalable.

---

## 📂 Estructura de Directorios

```plaintext
CRIPTOGRAFIA-CLASICA/
│
├── assets/
│   ├── css/
│   │   ├── input.css
│   │   └── styles.css
│   └── js/
│       └── form-handler.js
│
├── controllers/
│   └── procesar.php            # Lógica central de enrutamiento de peticiones
│
├── libs/
│   ├── desplazamiento/
│   │   └── palabra_clave.php   # Cifrado César con palabra clave
│   │
│   ├── sustitucion-monogramica/
│   │   ├── mono_afin.php       # Cifrado afín
│   │   ├── monogramica.php     # Sustitución directa
│   │   ├── hill.php            # Cifrado matricial Hill
│   │   └── playfair.php        # Cifrado Playfair
│   │
│   ├── sustitucion-polialfabetica/
│   │   ├── vernam.php          # Cifrado Vernam
│   │   └── vigenere.php        # Cifrado Vigenère
│   │
│   └── transposicion/
│   │   ├── columnas.php        # Transposición por columnas
│   │   ├── filas.php           # Transposición por filas
│   │   ├── grupos.php          # Transposición por grupos
│   │   ├── series.php          # Transposición en serie
│   │   ├── zigzag.php          # Transposición Zig-Zag
│   │
│   ├── anagramacion.php    # Anagramación
│   └── kasiski.php         # Análisis Kasiski (detección de periodicidad)
│
├── node_modules/               # Dependencias NPM (Tailwind, etc.)
│
├── package.json                # Definición de scripts y dependencias front
├── package-lock.json
├── tailwind.config.js          # Configuración de Tailwind CSS
│
├── views/
│   ├── tabs/
│   │   ├── displacement.php    # Pestaña Cifrado por desplazamiento
│   │   ├── substitution.php    # Pestaña Sustitución
│   │   ├── transposition.php   # Pestaña Transposición
│   │   └── advanced.php        # Pestaña Criptoanálisis avanzado
│   │
│   ├── header.php              # Cabecera común
│   ├── nav.php                 # Menú de navegación
│   ├── footer.php              # Pie de página
│   └── index.php               # Vista principal que incluye las pestañas
│
└── README.md                   # Este archivo
```

---

## 🚀 Cómo Empezar

1. **Clonar el repositorio**

   ```bash
   git clone https://tu-repo/CRIPTOGRAFIA-CLASICA.git
   cd CRIPTOGRAFIA-CLASICA
   ```

2. **Instalar dependencias front-end**

   ```bash
   npm install
   npm run dev        # Genera CSS/JS con Tailwind
   ```

3. **Levantar servidor PHP**

   - Con PHP integrado:
     ```bash
     php -S localhost:8000
     ```
   - O bien desplegar en tu entorno (Apache, Nginx, etc.)

4. **Abrir en el navegador**
   > http://localhost:8000/views/index.php

---

## 🧪 Pruebas Unitarias

> _(Opcional: crea la carpeta `test/` si deseas añadir PHPUnit)_

- Cada algoritmo debe contar con al menos 3 casos de prueba (normal, límite y error).
- Ejecuta con:
  ```bash
  phpunit --configuration phpunit.xml
  ```

---

## 📚 Documentación Técnica

- Cada archivo en `libs/` incluye:
  - Encabezado con descripción del algoritmo.
  - Fórmula matemática y pseudocódigo.
  - Ejemplo de uso y resultado esperado.
  - Notas sobre seguridad y vulnerabilidades.

---

## 🔄 Tecnologías Alternativas Recomendadas

Para futuros proyectos de criptografía o aplicaciones web similares, podrías considerar:

- **Back-end**

  - **PHP Frameworks**: Laravel, Symfony
  - **Node.js**: Express, NestJS + `crypto` nativo o `crypto-js`
  - **Python**: Flask o Django con PyCryptodome
  - **Go**: Gin + `crypto` estándar

- **Front-end**

  - **Frameworks**: React, Vue 3, Svelte
  - **UI Kits**: Bootstrap, Bulma, Chakra UI

- **Construcción y Bundling**

  - Vite, Webpack o Parcel en lugar de configuración manual de Tailwind

- **Pruebas**

  - PHPUnit (PHP), Jest/Mocha (JavaScript), PyTest (Python)

- **CI/CD y DevOps**
  - GitHub Actions, GitLab CI, Travis CI

---

> **¡Listo!** Con esta organización tendrás un README claro, mantenible y listo para escalar.
