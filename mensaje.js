function enviarMensaje(telefono) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if (response.success) {
                alert("Mensaje enviado correctamente a " + telefono);
            } else {
                alert("Hubo un error al enviar el mensaje: " + response.message);
            }
        } else if (this.readyState == 4) {
            alert("Hubo un error al enviar el mensaje: " + this.responseText);
        }
    };
    xhttp.open("POST", "enviar-mensaje.php", true);
    xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhttp.send(JSON.stringify({ telefono: telefono }));
}
