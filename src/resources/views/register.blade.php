<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConectaFP | Crear cuenta</title>
    @viteReactRefresh
    @vite(['resources/js/app.jsx'])
</head>

<body>
    <div id="app"></div>

    <main>

        <div id="form-container">
            <h1>Crear cuenta</h1>
            <form>
                <div class="form">
                    <label for="name">Nombre:</label>
                    <input type="text" id="name">
                </div>
                <div class="form">
                    <label for="last-name">Apellidos:</label>
                    <input type="text" id="last-name">
                </div>
                <div class="form">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password">
                </div>
                <div class="form">
                    <label for="email">Email:</label>
                    <input type="email" id="email">
                </div>
                <div class="form">
                    <label for="birthday">Fecha de nacimiento:</label>
                    <input type="date" id="birthday">
                </div>
                <input type="button" id="btnRegister" value="Crear cuenta">
            </form>
            <p>¿Tienes cuenta? <a href="/login">Inicie sesión</a></p>
        </div>
    </main>
</body>

</html>