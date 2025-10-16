class NuevoUsuario {
  constructor() {
    this.form = document.getElementById('registerForm');
    this.btnCreate = document.getElementById('createUserBtn');
    this.init();
  }

  init() {
    this.btnCreate.addEventListener('click', () => this.createUser());
  }

  async createUser() {
    const nombres = document.getElementById('new_nombres').value.trim();
    const apellidos = document.getElementById('new_apellidos').value.trim();
    const email = document.getElementById('new_email').value.trim();
    const password = document.getElementById('new_password').value.trim();

    if (!nombres || !apellidos || !email || !password) {
      alert('Por favor, complete todos los campos.');
      return;
    }

    const params = new URLSearchParams({
      new_nombres: nombres,
      new_apellidos: apellidos,
      new_email: email,
      new_password: password
    });

    try {
      const res = await fetch('../api/post_students.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
      });

      const response = (await res.text()).trim();

      if (response === 'success') {
        alert('¡Usuario creado exitosamente!');
        setTimeout(() => window.location.href = 'create_students.php', 700);
      } else if (response === 'usuario_existente') {
        alert('El correo ya está registrado. Intente con otro.');
      } else {
        alert('Ocurrió un error. Intente nuevamente.');
      }
    } catch (err) {
      console.error(err);
      alert('Error de conexión. Intente nuevamente.');
    }
  }
}

document.addEventListener('DOMContentLoaded', () => new NuevoUsuario());
