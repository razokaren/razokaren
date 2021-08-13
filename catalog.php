<!DOCTYPE html>
<html lang="es">
<head>
    <title>Carrera</title>
    <?php
        session_start();
        $LinksRoute="./";
        include './inc/links.php'; 
    ?>
    <script type="text/javascript" src="js/jPages.js"></script>
    <script>
        $(document).ready(function(){
           $('.list-catalog-container li').click(function(){
               window.location="catalog.php?CategoryCode="+$(this).attr("data-code-category");
           });
        });
    </script>
</head>
<body>
    <?php 
        include './library/configServer.php';
        include './library/consulSQL.php';
        include './process/SecurityUser.php';
        $VarCategoryCatalog=consultasSQL::CleanStringText($_GET['CategoryCode']);
        include './inc/NavLateral.php';
    ?>
    <div class="content-page-container full-reset custom-scroll-containers">
        <?php 
            include './inc/NavUserInfo.php';  
        ?>
        <div class="container">
            <div class="page-header">
              <h1 class="all-tittles">Sistema bibliotecario <small>Catálogo de libros</small></h1>
            </div>
        </div>
         <div class="container-fluid"  style="margin: 40px 0;">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <img src="assets/img/checklist.png" alt="pdf" class="img-responsive center-box" style="max-width: 110px;">
                </div>
                <div class="col-xs-12 col-sm-8 col-md-8 text-justify lead">
                    Bienvenido al catálogo, selecciona una categoría de la lista para empezar, si deseas buscar un libro por nombre o título has click en el icono &nbsp; <i class="zmdi zmdi-search"></i> &nbsp; que se encuentra en la barra superior
                </div>
            </div>
        </div>
        <?php
            $checkingAllBooks=ejecutarSQL::consultar("SELECT * FROM libro");
            if(mysqli_num_rows($checkingAllBooks)>0){
                echo '<div class="container-fluid" style="margin: 0 0 50px 0;"><h2 class="text-center" style="margin: 0 0 25px 0;">Categorías</h2><ul class="list-unstyled text-center list-catalog-container">';
                $checkCategory=ejecutarSQL::consultar("SELECT * FROM categoria order by Nombre ASC");
                if(mysqli_num_rows($checkCategory)>0){
                    while($fila=mysqli_fetch_array($checkCategory, MYSQLI_ASSOC)){
                        echo '<li class="list-catalog" data-code-category="'.$fila['CodigoCategoria'].'">'.$fila['Nombre'].'</li>'; 
                    }  
                }else{
                    echo '<p class="lead text-center all-tittles">No hay categorías registradas</p>';
                }
                mysqli_free_result($checkCategory);  
                echo '</ul></div>';
                if($VarCategoryCatalog==''){
                    echo '<p class="text-center lead all-tittles" style="padding: 0 25px;">Selecciona una categoría para empezar</p><br><br><br><br><br><br>';
                }else{

                $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
                mysqli_set_charset($mysqli, "utf8");

                $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                $regpagina = 30;
                $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

                $checkCodeBook=mysqli_query($mysqli,"SELECT SQL_CALC_FOUND_ROWS * FROM libro WHERE CodigoCategoria='$VarCategoryCatalog' ORDER BY Titulo ASC  LIMIT $inicio, $regpagina");

                $totalregistros = mysqli_query($mysqli,"SELECT FOUND_ROWS()");
                $totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);
        
                $numeropaginas = ceil($totalregistros["FOUND_ROWS()"]/$regpagina);

                if(mysqli_num_rows($checkCodeBook)>0){
                        $selectCategC=ejecutarSQL::consultar("SELECT * FROM categoria WHERE CodigoCategoria='".$VarCategoryCatalog."'");
                        $dataCategC=mysqli_fetch_array($selectCategC, MYSQLI_ASSOC);
                ?>
                <p class="text-center lead all-tittles text-lowercase" style="padding: 0 25px;">se muestra un total de <?php echo $totalregistros["FOUND_ROWS()"]; ?> libros en la categoría <?php echo $dataCategC['Nombre']; ?></p><br>
                <div class="container-fluid">
                <?php
					mysqli_free_result($selectCategC);
	                $countBook=$inicio+1;
                    $ctb=0;
	                while ($bookCodeInfo=mysqli_fetch_array($checkCodeBook, MYSQLI_ASSOC)){
                        if($ctb!=0):
	            ?>
                <div class="full-reset media-divider"></div>
                <?php endif; ?>
                <div class="media">
                    <div class="media-left media-top">
                        <a href="infobook.php?codeBook=<?php echo $bookCodeInfo['CodigoLibro']; ?>">
                            <?php if(is_file("./assets/uploads/img/".$bookCodeInfo['Imagen'])): ?>
                            <img src="./assets/uploads/img/<?php echo $bookCodeInfo['Imagen']; ?>" class="media-object img-media">
                           <?php else: ?>
                           <img src="./assets/img/book.png" class="media-object img-media">
                           <?php endif; ?>
                        </a>
                    </div>
                    <div class="media-body">
                        <h3 class="media-heading all-tittles"><?php echo $countBook.' - '.$bookCodeInfo['Titulo']; ?></h3>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-12 col-sm-4">
                                    <strong><i class="zmdi zmdi-account-box"></i> &nbsp; Autor:</strong>&nbsp;
                                    <?php echo $bookCodeInfo['Autor']; ?>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <strong><i class="zmdi zmdi-edit"></i> &nbsp; Editorial:</strong>&nbsp;
                                    <?php echo $bookCodeInfo['Editorial']; ?>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <strong><i class="zmdi zmdi-calendar-note"></i> &nbsp; Año:</strong>&nbsp;
                                    <?php echo $bookCodeInfo['Year']; ?>
                                </div>
                            </div>
                        </div>
                        <h5 class="all-tittles" style="padding-top: 15px;">
                            <strong><i class="zmdi zmdi-settings"></i> &nbsp; OPCIONES</strong>
                        </h5>
                        <div class="btn-media">
                            <?php if($bookCodeInfo['Download']=="yes" && is_file("./assets/uploads/pdf/".$bookCodeInfo['PDF'])): ?>
                            <a href="./assets/uploads/pdf/<?php echo $bookCodeInfo['PDF']; ?>" download="<?php echo $bookCodeInfo['Titulo']; ?>.pdf"  class="tooltips-general" data-placement="bottom" title="Descargar PDF">
                                <i class="zmdi zmdi-cloud-download"></i>
                            </a>
                            <a href="./assets/uploads/pdf/<?php echo $bookCodeInfo['PDF']; ?>" target="_blank" class="tooltips-general" data-placement="bottom" title="Ver PDF">
                                <i class="zmdi zmdi-eye"></i>
                            </a>
                            <?php else: ?>
                            <button type="button" class="text-mutted tooltips-general" data-placement="bottom" title="Descarga no disponible"><i class="zmdi zmdi-cloud-off"></i></button>
                            <button type="button" class="text-mutted tooltips-general" data-placement="bottom" title="Visualización no disponible"><i class="zmdi zmdi-eye-off"></i></button>
                            <?php endif; ?>
                            <a href="infobook.php?codeBook=<?php echo $bookCodeInfo['CodigoLibro']; ?>" class="tooltips-general" data-placement="bottom" title="Detalles y Préstamos">
                            <i class="zmdi zmdi-library"></i>
                        </a>
                        </div>
                    </div>
                </div>
	            <?php
	                    $countBook++;
                        $ctb++;
	                }
                ?>
                </div>
                <nav aria-label="Page navigation" class="text-center">
	                <ul class="pagination">
	                    <?php if($pagina == 1): ?>
	                        <li class="disabled">
	                            <a href="#" aria-label="Previous">
	                                <span aria-hidden="true">&laquo;</span>
	                            </a>
	                        </li>
	                    <?php else: ?>
	                        <li>
	                            <a href="catalog.php?CategoryCode=<?php echo $VarCategoryCatalog; ?>&pagina=<?php echo $pagina-1; ?>" aria-label="Previous">
	                                <span aria-hidden="true">&laquo;</span>
	                            </a>
	                        </li>
	                    <?php endif; ?>
	                    
	                    
	                    <?php
	                        for($i=1; $i <= $numeropaginas; $i++ ){
	                            if($pagina == $i){
	                                echo '<li class="active"><a href="catalog.php?CategoryCode='.$VarCategoryCatalog.'&pagina='.$i.'">'.$i.'</a></li>';
	                            }else{
	                                echo '<li><a href="catalog.php?CategoryCode='.$VarCategoryCatalog.'&pagina='.$i.'">'.$i.'</a></li>';
	                            }
	                        }
	                    ?>
	                    
	                    
	                    <?php if($pagina == $numeropaginas): ?>
	                        <li class="disabled">
	                            <a href="#" aria-label="Previous">
	                                <span aria-hidden="true">&raquo;</span>
	                            </a>
	                        </li>
	                    <?php else: ?>
	                        <li>
	                            <a href="catalog.php?CategoryCode=<?php echo $VarCategoryCatalog; ?>&pagina=<?php echo $pagina+1; ?>" aria-label="Previous">
	                                <span aria-hidden="true">&raquo;</span>
	                            </a>
	                        </li>
	                    <?php endif; ?>
	                </ul>
	            </nav> 
                <?php   
                    }else{
                        echo '<br><br><br><p class="lead text-center all-tittles">No hay libros registrados en esta categoría</p><br><br><br><br><br><br>'; 
                    }
                    mysqli_free_result($checkCodeBook);
                }
            }else{
                echo '<br><br><br><p class="lead text-center all-tittles">No hay libros registrados en el sistema</p><br><br><br><br><br><br>';
            }
            mysqli_free_result($checkingAllBooks);
        ?>
        <div class="modal fade" tabindex="-1" role="dialog" id="ModalHelp">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-center all-tittles">ayuda del sistema</h4>
                </div>
                <div class="modal-body">
                    <?php include './help/help-catalog.php'; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="zmdi zmdi-thumb-up"></i> &nbsp; De acuerdo</button>
                </div>
            </div>
          </div>
        </div>
        <?php include './inc/footer.php'; ?>
    </div>
</body>
</html>