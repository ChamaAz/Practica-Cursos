# 📚 Aplicación de Inscripción a Cursos de Formación

Aplicación web desarrollada en **PHP** para la gestión de inscripciones a cursos de formación de un centro de profesores.  
Permite **gestionar solicitudes, asignar plazas automáticamente según méritos y generar listados de admitidos**, todo de manera segura y eficiente.

---

## 🎯 Objetivos del proyecto

1. Gestionar inscripciones de profesores a cursos de formación.  
2. Automatizar la **asignación de plazas** según los méritos (puntuación) de cada solicitante.  
3. Garantizar que el número de admitidos nunca supere el número de plazas de cada curso.  
4. Ofrecer una interfaz web ágil y funcional para profesores y administradores.  
5. Implementar **autenticación de usuarios** para controlar el acceso a la aplicación.  
6. Utilizar buenas prácticas de PHP, incluyendo **uso generalizado de funciones**.  

---

## 💻 Funcionalidades principales

### Para solicitantes (profesores)
- Registro e inicio de sesión en la plataforma.  
- Acceso a un **formulario de inscripción** para los cursos activos.  
- Recepción de **resguardo en pantalla** y **correo de confirmación** al completar la inscripción.  
- Posibilidad de **inscribirse a varios cursos**, almacenando sus datos personales una sola vez.  

### Para administradores
- Activar o desactivar cursos.  
- Crear, modificar o eliminar cursos.  
- Mostrar listado de cursos activos.  
- Realizar **baremación automática** de solicitudes al finalizar el plazo de inscripción.  
- Mostrar listado de admitidos en cada curso.  

---

## 🛠 Funcionalidades técnicas

- **Inscripciones:** formulario web con lista de cursos activos.  
- **Baremación automática:** asigna plazas según méritos al finalizar el plazo de inscripción.  
- **Correo de confirmación:** al inscribirse correctamente.  
- **Gestión de cursos:** activación, desactivación, incorporación y eliminación de cursos.  
- **Listados dinámicos:** cursos activos, solicitantes admitidos, histórico de solicitudes.  
- **Autenticación de usuarios:** control de acceso para profesores y administradores.  
- **Código modular:** uso de funciones para mantener la aplicación organizada.  

---

## 🚀 Cómo ejecutar la aplicación

1. Clonar el repositorio:

```bash
git clone https://github.com/tuusuario/cursoscp-php.git
