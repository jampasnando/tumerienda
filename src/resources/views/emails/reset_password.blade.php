<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Restablecer contraseña</title>
    <style>
      :root {
        color-scheme: dark light;
        font-family: 'Nunito', sans-serif;
        background: #f4f7fb;
        color: #1f2a44;
      }
      * { box-sizing: border-box; }
      body {
        margin: 0;
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 24px;
        background: linear-gradient(180deg, #f7faff 0%, #eef4fb 100%);
      }
      .card {
        width: min(520px, 100%);
        background: #ffffff;
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 20px 60px rgba(19, 42, 86, 0.12);
        border: 1px solid rgba(31, 42, 68, 0.08);
      }
      h1 {
        margin: 0 0 10px;
        font-size: clamp(1.8rem, 2.4vw, 2.4rem);
        letter-spacing: -0.03em;
      }
      p.description {
        margin: 0 0 28px;
        color: #55617a;
        line-height: 1.7;
      }
      .field {
        display: grid;
        gap: 10px;
        margin-bottom: 18px;
      }
      label {
        font-weight: 700;
        font-size: 0.95rem;
      }
      input {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #ced7e4;
        border-radius: 14px;
        font-size: 1rem;
        background: #fafbff;
        color: #1f2a44;
      }
      input:focus {
        outline: none;
        border-color: #6a92ff;
        box-shadow: 0 0 0 4px rgba(106, 146, 255, 0.12);
      }
      button {
        width: 100%;
        border: none;
        border-radius: 14px;
        padding: 14px 18px;
        font-size: 1rem;
        font-weight: 700;
        color: #ffffff;
        background: linear-gradient(135deg, #ffb703, #fb8500);
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
      }
      button:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 24px rgba(251, 133, 0, 0.18);
      }
      .message {
        margin-top: 18px;
        padding: 14px 16px;
        border-radius: 14px;
        font-size: 0.95rem;
      }
      .message.error {
        background: #fff0f3;
        color: #a31f36;
        border: 1px solid #f3c2d1;
      }
      .message.success {
        background: #effaf4;
        color: #1d6f31;
        border: 1px solid #b8e4c3;
      }
      .small-note {
        margin-top: 12px;
        color: #5c6c85;
        font-size: 0.95rem;
      }
    </style>
  </head>
  <body>
    <div class="card">
      <h1>Restablecer contraseña</h1>
      <p class="description">Ingresa el nuevo password y confirma para actualizar tu cuenta.</p>

      <form id="reset-form">
        <input type="hidden" name="email" id="email" />
        <input type="hidden" name="token" id="token" />

        <div class="field">
          <label for="emailDisplay">Correo electrónico</label>
          <input type="email" id="emailDisplay" disabled />
        </div>

        <div class="field">
          <label for="password">Nueva contraseña</label>
          <input type="password" id="password" name="password" required minlength="6" autocomplete="new-password" />
        </div>

        <div class="field">
          <label for="passwordConfirmation">Confirmar contraseña</label>
          <input type="password" id="passwordConfirmation" name="password_confirmation" required minlength="6" autocomplete="new-password" />
        </div>

        <button type="submit">Cambiar contraseña</button>
      </form>

      <div id="status" class="message" style="display:none;"></div>
      <p class="small-note">El enlace es válido por 60 minutos. Si el enlace no funciona, solicita uno nuevo.</p>
    </div>

    <script>
      const params = new URLSearchParams(window.location.search);
      const token = params.get('token');
      const email = params.get('email');
      const statusEl = document.getElementById('status');
      const form = document.getElementById('reset-form');
      const emailInput = document.getElementById('email');
      const emailDisplay = document.getElementById('emailDisplay');
      const tokenInput = document.getElementById('token');

      function showMessage(text, type = 'error') {
        statusEl.textContent = text;
        statusEl.className = 'message ' + type;
        statusEl.style.display = 'block';
      }

      if (!token || !email) {
        showMessage('Falta token o correo en el enlace. Revisa el email de restablecimiento.', 'error');
        form.querySelector('button').disabled = true;
      } else {
        emailInput.value = email;
        emailDisplay.value = email;
        tokenInput.value = token;
      }

      form.addEventListener('submit', async function (event) {
        event.preventDefault();
        statusEl.style.display = 'none';

        const password = document.getElementById('password').value.trim();
        const passwordConfirmation = document.getElementById('passwordConfirmation').value.trim();

        if (password.length < 6) {
          showMessage('La contraseña debe tener al menos 6 caracteres.', 'error');
          return;
        }

        if (password !== passwordConfirmation) {
          showMessage('Las contraseñas no coinciden.', 'error');
          return;
        }

        try {
          const response = await fetch('/api/reset-password', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              email: emailInput.value,
              token: tokenInput.value,
              password: password,
              password_confirmation: passwordConfirmation
            })
          });

          const data = await response.json();

          if (!response.ok) {
            showMessage(data.message || 'Ocurrió un error al restablecer la contraseña.', 'error');
            return;
          }

          showMessage(data.message || 'Contraseña actualizada correctamente.', 'success');
          form.reset();
          form.querySelector('button').disabled = true;
        } catch (error) {
          showMessage('No se pudo conectar con el servidor. Intenta nuevamente.', 'error');
        }
      });
    </script>
  </body>
</html>
