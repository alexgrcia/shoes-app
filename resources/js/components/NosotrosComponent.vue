<style>
/* Estilos para el menú horizontal */
nav ul {
    list-style-type: none; /* Quitamos las viñetas de la lista */
    text-align: center; /* Centramos los ítems del menú */
}
nav li {
    display: inline; /* Ítems del menú en una línea horizontal */
    margin-right: 20px; /* Espacio entre los ítems */
}
nav a {
    text-decoration: none; /* Quitamos el subrayado de los enlaces */
    font-size: 18px; /* Tamaño de fuente */
    color: #333; /* Color del texto */
}
/* Estilos para la página "Nosotros" */
body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            color: #666;
        }
</style>
<template>
            <nav>
            <ul>
                <li><a href="/">Inicio</a></li>
                <li><a href="/nosotros">Sobre nosotros</a></li>
            </ul>
        </nav>
        <div class="container">
        <h1>Nosotros</h1>
        <p>En Shoes Store, nos apasiona ofrecer la mejor selección de calzado para nuestros clientes. Desde zapatillas deportivas hasta elegantes tacones, tenemos algo para todos.</p>
        <p>Nuestro equipo está comprometido con la calidad, el estilo y la comodidad. Trabajamos arduamente para asegurarnos de que encuentres el par perfecto para cada ocasión.</p>
        <p>¡Gracias por elegir Shoes Store para tus necesidades de calzado!</p>
    </div>
 </template>
