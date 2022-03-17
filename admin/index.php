<?php 

require '../includes/funciones.php';
$auth = estaAutenticado();

if(!$auth) {
    header('Location: /');
}


//IMPORTAR LA CONEXIÓN
require '../includes/config/database.php';
$db = conectarDB();

//ESCRIBIR EL QUERY
$query = "SELECT * FROM propiedades";

//CONSULTAR LA BD
$resultadoConsulta = mysqli_query($db, $query);


// lo que hace este placeholder '??' es buscar ese valor ('resultado') y si no existe
// entonces se asigna el NULL : es lo mismo que poner el: isset()

//Muestra un mensaje condicional
$resultado = $_GET['resultado'] ?? null;  

//esto es para revisar el request_method para que no marque undefine al hacer click en 'Eliminar' un archivo
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if($id) {
        
        //Eliminar el archivo
        $query = "SELECT imagen FROM propiedades WHERE id = ${id}";

        $resultado = mysqli_query($db, $query);
        $propiedad = mysqli_fetch_assoc($resultado);

        unlink('../imagenes/' . $propiedad['imagen']);

        //Eliminar la propiedad
        $query = "DELETE FROM propiedades WHERE id = ${id}";
        $resultado = mysqli_query($db, $query);

        if($resultado) {
            header('location: /admin?resultado=3');
        }
    }
}


//Incluye un template

incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>

        
        <?php if($resultado == 1): ?> 
            <p class="alerta exito">Anuncio Creado correctamente</p>
        <?php elseif($resultado == 2): ?>
            <p class="alerta exito">Anuncio Actualizado correctamente</p>
         <?php elseif($resultado == 3): ?>
            <p class="alerta exito">Anuncio Eliminado correctamente</p>
        <?php endif; ?>

        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>

        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Imagenes</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>

           <tbody> <!-- MOSTRAR LOS RESULTADOS  -->
           <!--antes del tr se debe crear el codigo que va a iterar con la bas de datos porque cada registro requiere un tr-->
              <?php while( $propiedad = mysqli_fetch_assoc($resultadoConsulta)): ?>
                <tr>  
                   <td><?php echo $propiedad['id']; ?></td>
                   <td><?php echo $propiedad['titulo']; ?></td>
                   <td> <img src="/imagenes/<?php echo $propiedad['imagen']; ?>" class="imagen-tabla"> </td>
                   <td>$ <?php echo $propiedad['precio']; ?></td>
                   <td>
                       <form method="POST" class="w-100">

                          <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>" >

                          <input type="submit" class="boton-rojo-block" value="Eliminar">
                       </form>
                       
                       <a href="admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" 
                           class="boton-amarillo-block w-100">Actualizar</a>
                   </td>
               </tr>
               <?php endwhile;?>
           </tbody> 
        </table>
    </main>


<?php 

//CERRAR LA CONEXION
mysqli_close($db);

incluirTemplate('footer');
?>