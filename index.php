<!DOCTYPE html>
<html lang="es">
<head>
    <title>Inicio de sesión</title>
    <?php
        $LinksRoute="./";
        include './inc/links.php'; 
    ?>
    <link rel="stylesheet" href="css/login.css"/>
    <script src="js/SendForm.js"></script>
</head>
<body class="full-cover-background" style="background-image:url(assets/img/font-login.jpg);">
    <div class="form-container">
        <p class="text-center" style="margin-top: 17px;">
           <i class="zmdi zmdi-account-circle zmdi-hc-5x"></i>
       </p>
       <h4 class="text-center all-tittles" style="margin-bottom: 20px;">inicia sesión con tu cuenta</h4>
       <form action="process/login.php" method="post" class="form_SRCB" data-type-form="login" autocomplete="off">
            <div class="group-material-login">
              <input type="text" class="material-login-control"  name="loginName" required="" maxlength="70">
              <span class="highlight-login"></span>
              <span class="bar-login"></span>
              <label><i class="zmdi zmdi-account"></i> &nbsp; Nombres</label>
            </div><br>
            <div class="group-material-login">
              <input type="password" class="material-login-control" name="loginPassword" required="" maxlength="70">
              <span class="highlight-login"></span>
              <span class="bar-login"></span>
              <label><i class="zmdi zmdi-lock"></i> &nbsp; Contraseña</label>
            </div>
            <div class="group-material">
                <select class="material-control-login" name="UserType">
                    <option value="" disabled="" selected="">Tipo de usuario</option>
                    <option value="Student">Estudiante</option>
                    <option value="Teacher">Docente</option>
                    <option value="Personal">Personal administrativo</option>
                    <option value="Admin">Administrador</option>
                </select>
            </div>
            <button class="btn-login text-uppercase" type="submit">Ingresar a PIB</button>
        </form>
    </div>  
    <div class="msjFormSend hidden"></div>
</body>
</html>