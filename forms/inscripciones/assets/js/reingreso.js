class NuevoUsuario {
  constructor() {
    this.modal = document.getElementById('codeModal');
    this.btnOpen = document.getElementById('openModal');
    this.btnVerify = document.getElementById('verifyCode');
    this.registerForm = document.getElementById('registerForm');
    this.bindEvents();
  }

  bindEvents() {
    this.btnOpen.addEventListener('click', () => this.openModal());
    this.btnVerify.addEventListener('click', () => this.verifyCode());
    this.modal.addEventListener('click', (e) => {
      if (e.target === this.modal) this.closeModal();
    });
  }

  openModal() {
    this.modal.classList.remove('hidden');
  }

  closeModal() {
    this.modal.classList.add('hidden');
  }

  async verifyCode() {
    const codigo = document.getElementById('codigo').value.trim();
    const nombres = document.getElementById('new_nombres').value.trim();
    const apellidos = document.getElementById('new_apellidos').value.trim();
    const email = document.getElementById('new_email').value.trim();
    const password = document.getElementById('new_password').value.trim();

    if (!codigo) return alert("Ingrese el código de verificación.");

    const params = new URLSearchParams({
      codigo,
      new_nombres: nombres,
      new_apellidos: apellidos,
      new_email: email,
      new_password: password
    });

    try {
      const res = await fetch("verificar_codigo.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: params.toString()
      });

      const response = (await res.text()).trim();

      switch (response) {
        case "success":
          alert("¡Usuario creado exitosamente! ¡Bienvenid@!");
          this.closeModal();
          setTimeout(() => (window.location.href = "formulario.php"), 500);
          break;
        case "codigo_usado":
          alert("El código ya fue utilizado. Solicita uno nuevo a administración.");
          break;
        case "codigo_invalido":
          alert("Código incorrecto. Intenta nuevamente.");
          break;
        case "error_crear_usuario":
          alert("Error al crear el usuario. Intenta más tarde.");
          break;
        default:
          alert("Respuesta inesperada del servidor.");
      }
    } catch (err) {
      alert("No se pudo verificar el código. Intente nuevamente.");
      console.error(err);
    }
  }
}

document.addEventListener('DOMContentLoaded', () => new NuevoUsuario());
