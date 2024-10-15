// Carga del SDK de MercadoPago
const script = document.createElement('script');
script.src = 'https://sdk.mercadopago.com/js/v2';
document.head.appendChild(script);

// Espera a que el SDK de MercadoPago esté cargado
script.onload = () => {
    // Inicialización de MercadoPago con tu clave pública
    const mp = new MercadoPago("TEST-13c64899-afce-4bd0-a16b-26502097f071");

    // Manejador del evento click del botón de compra
    document.getElementById("checkout-btn").addEventListener("click", async () => {
        try {
            // Datos del pedido
            const orderData = {
                items: productosEnCarrito.map(producto => ({
                    title: producto.titulo,
                    quantity: producto.cantidad,
                    unit_price: producto.precio, // Corrección aquí
                    currency_id: "MXN",
                }))
            };

            console.log("Body a enviar:", orderData); // Agregar esta línea para verificar el body

            // Envío del pedido al backend para obtener la preferencia de MercadoPago
            const response = await fetch("http://localhost:3004/create_preference", {
                method: "POST", 
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(orderData),
            });
        
            // Obtención de la preferencia de MercadoPago
            const preference = await response.json();
        
            // Creación del botón de pago
            createCheckoutButton(mp, preference.id);
        }
        catch(error){
            console.error("Error al procesar la solicitud:", error);
            alert("Error al procesar la solicitud");
        }
    });
};

// Función para crear el botón de pago
const createCheckoutButton = (mp, preferenceId) => {
    const checkout = mp.checkout({
        preference: {
            id: preferenceId
        }
    });

    checkout.render({
        container: '#wallet_container',
        label: 'Pagar',
    });
};
