# 🔐 Proyecto de Criptografía Clásica en PHP

Este proyecto implementa algoritmos de cifrado y descifrado clásicos con librerías modulares en PHP, interfaz visual, pruebas unitarias y documentación técnica. Su estructura permite el trabajo **en paralelo por cuatro personas**, cada una encargada de un módulo.

---

## 📁 Estructura del Proyecto
CRIPTOGRAFIA-CLASICA/
│
├── assets/ # Estilos CSS y scripts JS
├── config.php # Configuración general
├── controllers/ # Lógica central del controlador
│   └── procesar.php
├── libs/ # Librerías de cifrado
│   ├── desplazamiento/
│   │   └── palabra_clave.php
│   ├── s monogramica polialfabeto/
│   │   ├── hill.php
│   │   ├── kasiski.php
│   │   ├── playfair.php
│   │   └── polialfabeto_periodico.php
│   ├── sustitucion/
│   │   ├── mono_afin.php
│   │   ├── monogramica.php
│   │   └── polialfabetica.php
│   └── transposicion/
│       ├── columnas.php
│       ├── filas.php
│       ├── grupos.php
│       ├── series.php
│       └── zigzag.php
├── test/ # Pruebas unitarias
├── views/ # Interfaz visual (HTML/PHP)
├── index.php # Punto de entrada de la aplicación
└── README.md

---

## 👥 Distribución de Trabajo por Persona

### 🧩 Persona 1 – **Cifras por Sustitución**
📂 `libs/sustitucion/`

#### Archivos:
- `mono_afin.php`
- `monogramica.php`
- `polialfabetica.php`

#### Responsabilidades:
- Implementar cifrado y descifrado para cada método.
- Validar claves: afín debe tener `a` coprimo con 26.
- Prevenir símbolos no permitidos en entrada.
- Documentar cada algoritmo con ejemplos.
- Pruebas con entradas comunes y borde.

#### Puntos clave:
- Comparación de frecuencia al cifrar texto largo.
- Mostrar vulnerabilidad de monogramas.
- Diferenciar entre clave de sustitución estática vs dinámica.

---

### 🧩 Persona 2 – **Cifras por Transposición**
📂 `libs/transposicion/`

#### Archivos:
- `columnas.php`, `filas.php`, `grupos.php`, `series.php`, `zigzag.php`

#### Responsabilidades:
- Implementar lógica de permutación de índices.
- Documentar con esquemas de entrada/salida visuales.
- Manejar padding para completar celdas si hay texto incompleto.
- Realizar pruebas de reversibilidad.

#### Puntos clave:
- Zig-Zag debe permitir dirección y número de filas como parámetros.
- Columnas y filas deben poder usar una clave (orden de columnas).

---

### 🧩 Persona 3 – **Algoritmos Matriciales y Criptoanálisis**
📂 `libs/s monogramica polialfabeto/`

#### Archivos:
- `hill.php`
- `playfair.php`
- `kasiski.php`
- `polialfabeto_periodico.php`

#### Responsabilidades:
- Usar álgebra modular para Hill (inversas de matrices).
- En PlayFair, manejar letras duplicadas y letras sin pareja.
- Simular un ataque de Kasiski (detección de periodicidad).
- Validar claves y tamaño (ej. Hill necesita cuadrado perfecto).

#### Puntos clave:
- Inversión de matrices en Hill solo con determinante invertible.
- Uso de digramas en PlayFair (pares de letras).
- Mostrar visualmente cómo se rompe un cifrado con Kasiski.

---

### 🧩 Persona 4 – **Cifra César + Backend + Testing + Interfaz Visual**
📂 `libs/desplazamiento/`, `controllers/`, `test/`, `index.php`, `views/`, `assets/`

#### Archivos:
- `palabra_clave.php`
- `procesar.php`
- `test/` (todos los archivos de prueba)
- `index.php`
- `views/` (formularios HTML para cada algoritmo)
- `assets/` (estilos CSS y scripts JS si se usan)

#### Responsabilidades:
- Implementar el cifrado César con palabra clave.
- Diseñar y construir toda la **interfaz visual web**.
- Gestionar el flujo de entrada/salida de la app.
- Conectar el frontend con las librerías mediante `procesar.php`.
- Preparar pruebas unitarias centralizadas.
- Coordinar que todos los módulos funcionen desde el frontend.
- Diseñar layout claro, responsive, con mensajes de error amigables.

#### Puntos clave:
- Validar entradas desde el formulario HTML antes de enviarlas.
- Mostrar texto cifrado y descifrado en la misma vista.
- Usar Bootstrap o CSS básico para diseño limpio.
- Agregar un `<select>` con todos los algoritmos disponibles.
- Preparar presentación técnica y demo final (modo usuario).

---

## 🧪 Pruebas Unitarias

Cada archivo en `test/` debe:
- Ejecutar al menos 3 casos por algoritmo.
- Incluir pruebas normales, límite, y casos con errores.
- Mostrar “✔️ OK” o “❌ Error” en consola.

Ejemplo básico:
```php
include '../libs/sustitucion/mono_afin.php';
$texto = "HOLA";
$clave = [5, 8];
$esperado = "XUBI";
$resultado = cifrar($texto, $clave);
echo $resultado === $esperado ? "✔️ OK" : "❌ Error";
```

---

## 📚 Documentación

Cada archivo PHP debe incluir:
- Encabezado con descripción del algoritmo
- Fórmula matemática y pseudocódigo
- Ejemplo de uso y resultado esperado
- Notas de seguridad y debilidades (si aplica)

---

## 📊 Comparativa Técnica (para presentación final)

| Algoritmo               | Tipo         | Complejidad | Clave Requerida     | Vulnerabilidad Principal |
|-------------------------|--------------|-------------|----------------------|---------------------------|
| César con clave         | Sustitución  | O(n)        | Palabra clave        | Frecuencia                |
| Afín                    | Sustitución  | O(n)        | a,b (mod 26)         | Frecuencia                |
| Polialfabética          | Sustitución  | O(n)        | Cadena clave         | Kasiski                   |
| PlayFair                | Matricial    | O(n)        | 5x5 matriz           | Bigrama                   |
| Hill                    | Matricial    | O(n²)       | Matriz invertible    | Clave débil, no inversa   |
| Transposición Zigzag    | Transposición| O(n)        | filas/orden          | Visual                    |

---

## ✅ Recomendaciones Finales

- Mantener los módulos 100% independientes y reutilizables.
- Usar `mb_strtoupper()` o `mb_substr()` si hay tildes o UTF-8.
- Evitar errores silenciosos; capturar excepciones y mostrar mensaje.
- Incluir comentarios en el código y explicar paso a paso.