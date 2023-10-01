<?php
include("./head.php");
?>
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="./public/css/header.css">
    <link rel="stylesheet" href="./public/css/estilos-formularios.css">
</head>

<body>
    <header>
        <div class="logo-container">
            <a href="./index.php"> <img src="./public/images/logos/logo-white.png" alt="logo-drinkstore"></a>
        </div>
        <div class="burguer-navbar-container">
            <div class="menu-container">
                <div class="menu-hamburguesa-container">
                    <i class="fa-solid fa-bars"></i>
                </div>
                <div class="menu-navbar">
                    <ul>
                        <li>
                            <p><a href="/index.php">
                                Inicio
                            </a>
                            </p>
                        </li>
                        <li>
                            <p><a href="#nosotros">
                                Nosotros
                            </a>
                            </p>
                        </li>
                        <li>
                            <p><a href="#contacto">
                                Contacto

                            </a>
                            </p>
                        </li>
                        <li>
                            <p><a href="#ubicacion">
                                Ubicacion

                            </a>
                            </p>
                        </li>
                        <li>
                            <p><a href="#redes">
                                Redes

                            </a>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="cart-login-container">
                <div class="icon-cart-container">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div class="icon-logout-container">
                    <i class="fa-solid fa-right-from-bracket logout-icon"></i>
                </div>
                <div class="login-register">
                    
                    <div class="login-cont">
                        <p class="login">
                            <a href="./login.php">Login</a>
                        </p>
                    </div>
                    <div class="register">
                        <p>
                            <a href="./registro.php">Registro</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <form class="form-registro" action="php/insertar_cliente.php" method="POST">
        <h5>Formulario de Registro</h5>
        <input class="controls" type="text" name="nombre"  placeholder="  Ingrese su Nombre" required>
        <input class="controls" type="text" name="apellido"  placeholder="  Ingrese su Apellido"required>
        <input class="controls" type="date"  name="fechaNacimiento"  placeholder="  Su fecha de nacimiento"required>
        <input class="controls" type="text" name="mail"  placeholder="  Ingrese su Mail">
        <input class="controls" type="number" name="dni"  placeholder="  Ingrese su DNI">
        <input class="controls" type="number" name="cuil-cuit"  placeholder="  Ingrese su CUIL/CUIT">
        <input class="controls" type="number" name="telNum"  placeholder="  Ingrese su Telefono"required>
        <input class="controls" type="text" name="direccion"  placeholder="  Ingrese su Direccion">
        <label for="tipo_cliente">Seleccione su categoria</label>
        <select class="controls" name="tipo_cliente" id="tipo_cliente" placeholder="ingrese su cateogria">
            <option value="1">Responsable Inscripto</option>
            <option value="2">Responsable NO Inscripto</option>
            <option value="3">Monotributista</option>
            <option value="4">Exento</option>
            <option value="5">Consumidor Final</option>
        </select>
        <input class="controls" type="password" name="pass"  placeholder="  Ingrese su contrasena" required>
        <input class="controls" type="password" name="confirmPass"  placeholder="  Ingrese su contrasena nuevamente" required>
        <input class="boton" type="submit" name="" value="Registrar">
        <p>
        <a  href="index.php">Volver a la página principal</a> <br>
        Ya tienes una cuenta?<a href="./login.php"> Inicia Sesión </a></p>

    </form>
</body>