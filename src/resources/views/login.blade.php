<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConectaFP | Iniciar sesión</title>
    @viteReactRefresh
    @vite(['resources/js/app.jsx'])
</head>

<body>
    <div id="app"></div>

    <main>
        <div id="form-container">
            <h1>Iniciar sesión</h1>
            <form>
                <div class="form">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password">
                </div>
                <div class="form">
                    <label for="email">Email:</label>
                    <input type="email" id="email">
                </div>
                <input type="button" id="btnLogin" value="Iniciar sesión">
            </form>
            <p>¿No tienes cuenta? <a href="/register">Cree una cuenta</a></p>
        </div>
    </main>
</body>

</html>