<?php 
require 'includes/funciones.php';
incluirTemplate('header');
?>

    <main class="contenedor seccion contenido-centrado">
        <h1>Guía para la decoración de tu hogar</h1>

        <picture>
            <source srcset="build/img/destacada2.webp" type="image/webp">
            <source srcset="build/img/destacada2.jpg" type="image/jpeg">
            <img loading="lazy" src="build/img/destacada2.jpg" alt="Imagen de la Propiedad">            
        </picture>

        <p class="informacion-meta">Escrito el: <span>20/10/2021</span> por: <span>Admin</span> </p>

        <div class="resumen-propiedad">
                
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid facilis quas tenetur quasi fugiat numquam,
                vitae consequatur quam corporis, dolorum ex reiciendis eaque culpa recusandae aperiam fugit voluptates. Tempore, explicabo!
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid facilis quas tenetur quasi fugiat numquam,
                vitae consequatur quam corporis, dolorum ex reiciendis eaque culpa recusandae aperiam fugit voluptates. Tempore, explicabo!
            </p>
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid facilis quas tenetur quasi fugiat numquam,
                vitae consequatur quam corporis, dolorum ex reiciendis eaque culpa recusandae aperiam fugit voluptates. Tempore, explicabo!
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid facilis quas tenetur quasi fugiat numquam,
                vitae consequatur quam corporis, dolorum ex reiciendis eaque culpa recusandae aperiam fugit voluptates. Tempore, explicabo!
            </p>
        </div>
    </main>


<?php 
incluirTemplate('footer');
?>