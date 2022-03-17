<?php 

require '../../includes/funciones.php';
$auth = estaAutenticado();

if(!$auth) {
    header('Location: /');
}


//Base de datos
require '../../includes/config/database.php';
$db = conectarDB();

//Consultar para obtener los vendedores
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

//Arreglo con mensaje de errores
$errores = [];

$titulo ='';
$precio = '';
$descripcion = '';
$habitaciones = '';
$wc = '';
$estacionamiento = '';
$vendedorId = '';

//Ejecuta el código despues de que el usuario envia el formulario
if($_SERVER['REQUEST_METHOD'] === 'POST') {



   /*  echo "<pre>";
        var_dump($_POST);
    echo "</pre>"; */

    $titulo = mysqli_real_escape_string( $db, $_POST['titulo'] );
    $precio = mysqli_real_escape_string( $db, $_POST['precio'] );
    $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion'] );
    $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones'] );
    $wc = mysqli_real_escape_string( $db, $_POST['wc'] );
    $estacionamiento = mysqli_real_escape_string( $db, $_POST['estacionamiento'] );
    $vendedorId = mysqli_real_escape_string( $db, $_POST['vendedor'] );
    $creado = date('Y/m/d');

    //Asignar files hacia una variable
    $imagen = $_FILES['imagen'];

    if(!$titulo) {
        $errores[] = "Se debe añadir título";
    }
    if(!$precio) {
        $errores[] = "Se debe añadir el precio";
    }
    if( strlen ($descripcion) < 20 ) {
        $errores[] = "La descricpion es obligatoria y debe tener al menos 20 caracteres";
    }
    if(!$habitaciones) {
        $errores[] = "Se debe añadir el numero de habitaciones";
    }
    if(!$wc) {
        $errores[] = "Se debe añadir numero de baños";
    }
    if(!$estacionamiento) {
        $errores[] = "Se debe añadir numero de estacionamientos";
    }
    if(!$vendedorId) {
        $errores[] = "Elige un vendedor";
    }
    if(!$imagen['name'] || $imagen['error']) {
        $errores[] = 'La imagen es Obligatoria';
    }

    //Validar por tamaño (1000kb máximo)
    $medida = 1000 * 1000;

    if($imagen['size'] > $medida) {
        $errores[] = 'La imagen es muy pesada';
    }
    

    //Revisar que el array de Errores esté vacio
    if(empty($errores)) {

        /**Subida de Archivos */

        //Crear Carpeta
        $carpetaImagenes = '../../imagenes/';

        if(!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }

        //Generar un nombre unico para cuando se carguen archivos imagenes
        $nombreImagen = md5( uniqid( rand(), true )) . "jpg";



        //Subir la imagen. move_uploaded_file: Es para crear archivos (carpetas)
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);
        
        

        //Insertar en la base de datos, codigo sql en PHP
        $query = "INSERT INTO propiedades(titulo, precio, imagen, descripcion, 
        habitaciones, wc, estacionamiento, creado, vendedorId) 
        VALUES ('$titulo', '$precio', '$nombreImagen', '$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$creado', '$vendedorId')";

        //echo $query; es para saber si se ha cargado de manera correcta los datos de prueba y muestra en pantalla

        $resultado = mysqli_query($db, $query);

        if($resultado) {
            //redireccionar al usuario para que no reenvie al llenar los datos correctamente y crea que no se envió
            header('Location: /admin?resultado=1');
        }
   }
}

        
        incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Crear</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>
           
        <!-- foreach se ejecuta el menos una vez si hay algun elemento dentro del array, en este caso dentro de $error, si no hay elementos no ejecuta nada -->
        <?php foreach($errores as $error): ?> 
           
            <div class="alerta error">
            <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        
        <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
            <fieldset>
                <legend>Informacion General</legend>

                <label for="titulo">Titulo: </label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>"> <!-- El value que colocamos es para validar cada campo y no perder esos datos que se cargó al hacer refresh a la pagina o si marca error en algun campo con que se haya colocado mal los datos -->

                <label for="precio">Precio: </label>
                <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

                <label for="imagen">Imagen: </label>
                <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>
            </fieldset>

            <fieldset>
                <legend>Información Propiedad</legend>
                
                <label for="habitaciones">Habitaciones: </label>
                <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones; ?>">
                
                <label for="wc">Baños: </label>
                <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc; ?>">

                             
                <label for="estacionamiento">Estacionamiento: </label>
                <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento; ?>">
            </fieldset>

            <!-- se usa el : "dos puntos" de esa manera para no usar llaves "{}" y asi cerrar con "endwhile" es solo un manera de escribir codigo -->
            <!--  mysqli_fetch_assoc es usada para regresar una representación asociativa de la siguiente fila en el resultado, representado por el parámetro resultado , donde cada llave en la matriz representa el nombre de las columnas en el resultado. -->
            <fieldset>
               
                  <legend>Vendedor</legend>

                <select name="vendedor">
                    <option value="">--Seleccione--</option>                      
                    <?php while($vendedor = mysqli_fetch_assoc($resultado)) : ?> 
                    
                    <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>"> <?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?> </option>

                    <?php endwhile; ?>
                 </select>                                                           
            </fieldset>

            <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </form>
        
    </main>


<?php 
incluirTemplate('footer');
?>