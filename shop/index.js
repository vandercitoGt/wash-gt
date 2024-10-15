import express from "express";
import cors from "cors";
import { MercadoPagoConfig, Preference } from 'mercadopago';

// Configura Mercado Pago con tu token de acceso
const client =  new MercadoPagoConfig({ accessToken: "TEST-4037026436596388-050711-6469b583a4c86380c68db957eee4b10e-1803150950"});

const app = express();
const port = 3004;

app.use(cors());
app.use(express.json());

app.get("/", (req, res) => {
  res.send("Server On");
});

app.post("/create_preference", async (req, res) => {
  try {
    const body = {
      items: req.body.items, // No hay necesidad de modificar aquí, ya se recibe el body correctamente del frontend
      back_urls: {
        success: "https://www.facebook.com",
        failure: "https://www.facebook.com",
        pending: "https://www.facebook.com",
      },
      auto_return: "approved",
    };

    console.log("Body recibido:", body);

    const preference = new Preference(client);
    const result = await preference.create({ body });

    console.log("Resultado de la preferencia creada:", result);

    res.json({
      id: result.id,
    });
  } catch (error) {
    console.error("Error al procesar la solicitud:", error);
    res.status(500).json({
      error: "Error al crear la preferencia :("
    });
  }
});

app.listen(port, () => {
  console.log(`Server On Port: ${port}`);
});
