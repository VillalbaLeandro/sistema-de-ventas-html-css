<?php
include_once "encabezado.php";
?>
<div class="container">
    <div class="row m-5 no-gutters shadow-lg d-flex justify-content-center">

        <div class="col-md-6 ">
            <img src="logo_principal.png" class="img-fluid my-4"  />
        </div>
        <h3 class="pb-3">Iniciar sesión como usuario del sistema</h3>
        <div>
            <form action="iniciar_sesion.php" method="post">
                <div class="form-group pb-3">
                    <input type="text" placeholder="Usuario" class="form-control" name="nombre" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                </div>
                <div class="form-group pb-3">
                    <input type="password" placeholder="Contraseña" class="form-control" name="password" id="exampleInputPassword1" required>
                </div>

                <div class="pb-2">
                    <button type="submit" name="ingresar" class="btn btn-primary w-100 font-weight-bold mt-2">Ingresar</button>
                </div>
                <div class="pb-2">
                    <a class="btn btn-dark w-100 font-weight-bold mt-2" href="../index.php">Regresar al Inicio</a>
                </div>
            </form>
        </div>
    </div>
</div>