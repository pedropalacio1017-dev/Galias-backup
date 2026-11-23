const { google } = require("googleapis");
const process = require("process");
const express = require("express");
const axios = require("axios");
const { wrapper } = require("axios-cookiejar-support");
const tough = require("tough-cookie");
const mysql = require("mysql2/promise");
const FormData = require("form-data");

const META_PAGE_ACCESS_TOKEN = process.env.META_PAGE_ACCESS_TOKEN;
const META_PAGE_ID = process.env.META_PAGE_ID;

if (!META_PAGE_ACCESS_TOKEN) {
  throw new Error("Falta configurar META_PAGE_ACCESS_TOKEN");
}

if (!META_PAGE_ID) {
  throw new Error("Falta configurar META_PAGE_ID");
}

const META_VERIFY_TOKEN = process.env.META_VERIFY_TOKEN;

if (!META_VERIFY_TOKEN) {
  throw new Error("Falta configurar META_VERIFY_TOKEN");
}

const db = mysql.createPool({
  host: process.env.WORDPRESS_DB_HOST || "10.0.19.80",
  user: process.env.WORDPRESS_DB_USER || "bd_galias",
  password: process.env.WORDPRESS_DB_PASSWORD,
  database: process.env.WORDPRESS_DB_NAME || "bd_galias",
  waitForConnections: true,
  connectionLimit: 10,
});
const multer = require("multer");
const upload = multer({
  storage: multer.memoryStorage(),
  limits: { fileSize: 20 * 1024 * 1024 },
});

const jar = new tough.CookieJar();
const client = wrapper(
  axios.create({
    jar,
    withCredentials: true,
  }),
);
const cors = require("cors");

const SCOPES = ["https://www.googleapis.com/auth/spreadsheets"];

if (!process.env.GOOGLE_SERVICE_ACCOUNT_JSON) {
  throw new Error("Falta configurar GOOGLE_SERVICE_ACCOUNT_JSON");
}

let serviceAccountCredentials;

try {
  serviceAccountCredentials = JSON.parse(
    process.env.GOOGLE_SERVICE_ACCOUNT_JSON,
  );
} catch (error) {
  throw new Error("GOOGLE_SERVICE_ACCOUNT_JSON no contiene un JSON válido");
}

const googleAuth = new google.auth.GoogleAuth({
  credentials: serviceAccountCredentials,
  scopes: SCOPES,
});

const app = express();
const port = 3000;

const allowedOrigins = [
  "https://galias.greatness.com.co",
  "https://www.galias.greatness.com.co",
];

app.use(
  cors({
    origin: (origin, callback) => {
      if (!origin || allowedOrigins.includes(origin)) {
        return callback(null, true);
      }

      return callback(new Error("Origen no permitido"));
    },
    methods: ["GET", "POST", "OPTIONS"],
    allowedHeaders: ["Content-Type", "Authorization"],
  }),
);

app.options("*", cors());

app.use(express.json());

function splitFullName(fullName) {
  if (!fullName || fullName === "NO DATA") {
    return { firstName: "NO DATA", lastName: "NO DATA" };
  }

  const parts = fullName.trim().split(/\s+/);

  if (parts.length === 1) {
    return { firstName: parts[0], lastName: "NO DATA" };
  }

  return {
    firstName: parts.slice(0, -1).join(" "),
    lastName: parts.slice(-1).join(" "),
  };
}

// --- Google Auth helpers ---
async function authorize() {
  return await googleAuth.getClient();
}

async function appendValues(auth, values, spreadsheetId) {
  const sheets = google.sheets({ version: "v4", auth });
  const res = await sheets.spreadsheets.values.append({
    spreadsheetId,
    range: "Hoja 1",
    valueInputOption: "USER_ENTERED",
    resource: { values },
  });
  console.log(`${res.data.updates.updatedCells} cells appended.`);
  return res;
}

// --- Endpoint principal ---
app.post("/insertData", async (req, res) => {
  console.log("Webhook recibido:", JSON.stringify(req.body, null, 2));

  res.status(200).json("ok");
  console.log("[META] webhook recibido correctamente");

  try {
    // --- validar que el webhook pertenezca a Constructora las Galias ---
    const pageId = req.body?.entry?.[0]?.id;

    if (pageId !== META_PAGE_ID) {
      console.warn(
        "[META] Webhook recibido de una página no autorizada:",
        pageId,
      );
      return;
    }

    // Token exclusivo de Constructora las Galias
    const accessPageToken = META_PAGE_ACCESS_TOKEN;

    // --- validar que exista información del lead ---
    if (!req.body?.entry?.[0]?.changes?.[0]) {
      return;
    }
    const leadgenId = req.body.entry[0].changes[0].value.leadgen_id;
    const formId = req.body.entry[0].changes[0].value.form_id;
    console.log(
      "Leadgen ID:",
      leadgenId,
      "Form ID:",
      formId,
      "Page ID:",
      pageId,
    );

    // --- spreadsheetId según formId ---
    let spreadsheetId = "1nhPxcSxbH_ZsCHDC7mrc9lBtgMRPQdGGWE6w0Hi-SvY"; // default

    switch (formId) {
   
      // INICIO DE MIGRACIÓN FORMULARIOS NP (NUEVO PORTAFOLIO) Y PG (PORTAFOLIO GREATNESS)
      // SE MANTENDRÁ ESTA LÓGICA DE SWITCH MIENTRAS SE MIGRA TODO DESDE META Y PORSTERIOR A LA MIGRACIÓN, SE CAMBIARÁ LA LÓGICA DEL SWITCH.
      // SE RECOMIENDA NO MODIFICAR EL SWITCH UNA VEZ SE CONFIRME EL TOTAL DE LOS FORMS MIGRADOS PARA CAMBIAR LA LÓGICA.

      // BOREAS 175 (Bogotá)new
      case "878987361906641":
        spreadsheetId = "194MtDr-tjouID4XiwGDCZsvmiAp87RdYdcCiISLosdQ";
        break;
      // ZONA IN (Bogotá)new
      case "1708716687472666":
        spreadsheetId = "1VYinpRYKBCRWWVn75ZqASrwU2L2z-S6gU3_mRYHHwxE";
        break;
      // NEXO 119 (Bogotá)new
      case "1780777029676579":
        spreadsheetId = "1g_bYBeps0cWleiCkzqWhZ7BHECY3TnzIdkG2NHPRJKs";
        break;
       // CENTRUM (Bogotá)new
      case "2090245551605880":
        spreadsheetId = "12Vh4UyjJjdqxQ_LQ_bnjJPdbQO7TYZojJkObQwtEOh8";
        break;
      // MIRADOR DE LOS ALCAZARES (Manizales)new
      case "1000803512278158":
        spreadsheetId = "1NGn4zVtOnLlXSMRKOmAct7U4w0SVRA9PBE0_FTHAMlk";
        break;
      // CIUDAD FLORAL HELICONIAS (Manizales)new
      case "2552318348583571":
        spreadsheetId = "1k-ZRPIkcCw8wD1s0bBkiZCsx9sEZsg915GJGkFKUKSg";
        break;
      // DOSQUEBRADAS- CIUDAD CAMPESTRE (Pereira)new
      case "164659060021032":
        spreadsheetId = "1Cd8VX15wBjRCUxM_fDlWdvdvjt7s1_Fk0-Dwob48CJY";
        break;
      // ALAMOS 70 (Cali)new
      case "1436935461202376":
        spreadsheetId = "1mrrn63ppk9EjO303KJtDe37W2U1GMqnhRrNTeWvBOqc";
        break;
      // FORMULARIOS NUEVOS PORTAFOLIO GREATNESS
      // ESTACIÓN FONTIBÓN (Bogotá)new
      case "28623490767332982":
        spreadsheetId = "1aa3E1dtw5-g6DT0Byx6qORcJPO7yTlQVC3EPB-bcgtA";
        break;
      // VÉRTICE 115 (Bogotá)new
      case "1662874588608368":
        spreadsheetId = "1NcVqLox2J7aDp3CJWT9E4Kp9cT79L7U7EUH9l2hKllc";
        break;
      // FILMTEX- ÁGORA (Bogotá)new
      case "1800395961751536":
        spreadsheetId = "15jvOeY6QR2qravpSUIrixWfBmzXVVC-PGoKQjlOyc-c";
        break;
      // BOSQUES DEL MEDITERRANEO (Bogotá)new
      case "28834612506194778":
        spreadsheetId = "1_WdH96hThU-rx0c1uHsLXSF3q-xMuTwAglXZOv7u21c";
        break;
      // CAMPAÑA INVERSIONISTAS MENGA (cali)new
      case "1132702419516138":
        spreadsheetId = "1VyLzpsGVJz1NjTZ9qjCeocoLQiKUbNTf5CQ0dOqYDMY";
        break;
      // CAMPAÑA INVERSIONISTAS CHIPICHAPE(cali)new
      case "1650041486522779":
        spreadsheetId = "12uQJpapPNVaiAbFRCeLG6vlEwTu2FIu1-9ofyKDKj28";
        break;
      // CHIPICHAPE (Cali)new
      case "1918509229111700":
        spreadsheetId = "1OWUsgYBLLOKnK4dOjjIcWyPaKAGi2OlT7boYUHoFo-M";
        break;
       // BRISAS DE BELMONTE (Pereira)new
      case "2599037413929531":
        spreadsheetId = "1no80SGYtQhuj_cKBkEGiN9ThRkNqlpizfJ3GuhK-t7o";
        break; 
      // ALBORADA DE CUBA (Pereira)new
      case "1818519262724363":
        spreadsheetId = "1FAs2A1ei7o-mDYqqd90aWPkzjpvBhnU6tPn_-pQ_HWo";
        break;
      // ALAMOS 70 (Cali)new
      case "1555908419913779":
        spreadsheetId = "1mrrn63ppk9EjO303KJtDe37W2U1GMqnhRrNTeWvBOqc";
        break;
        // Bosques de Cuba new
      case "1615669196255369":
        spreadsheetId = "1FxUqtfYtqu999ryoIhD0E1uILh30VYtVwRxsTb2tlj8";
        break;
        // Menga new
      case "1704378214368396":
        spreadsheetId = "10NWwRu989iNm6RScNKG4L72Vc4_oXSYeO3bxCc1V0Vc";
        break;
        // Altavista 2000 new
      case "1008101531578135":
        spreadsheetId = "1e3OPJGJqdfL80v4tJvsfOSBXciBzQBZ6GDugyED6I3Q";
        break;
        // Altavista del parque (Pereira) new
      case "1801918594429655":
        spreadsheetId = "1imWow0mcRJiWHX_rEsaRAnpYbBT_-wD8MitlvyOYKAI";
        break;
        // Casa Blanca (Bogota) new
      case "1484660757102278":
        spreadsheetId = "1cH3rMeU-2eivo6oJgZDn_QMxBKiPcJMf8JdrNGWZRYk";
        break;
        // Molinos (Bogota) new
      case "1141520065534695":
        spreadsheetId = "1JWu77MhvZaOXMbRRednMtiW5dMatbt39KT-6zhN4fw";
        break;
        // Foresta de la Sultana (Manizales) new
      case "1122049113859428":
        spreadsheetId = "1YtLZDc3mfOM4Fbwu_bUHnnA9G8Wn7dqHBzhEHBs_Khk";
        break;
        // Portal de los cambulos (Manizales) new
      case "1837436944252346":
        spreadsheetId = "1UvULgLz_NH-IW35HtihQn0Zt-FGQFZpT-PLiMTHHJAo";
        break;
        // Ronda de Verano (Bogotá) new
      case "1881592923184994":
        spreadsheetId = "1nD-gpsSqfRfhigJzeJh7XiedGUGw2JARUw5Ak1Tmd9o";
        break;
       // CIUDADELA ALTARIA (Bogotá) new
      case "2280183449213153":
        spreadsheetId = "1WBt147Xj6XAOgcbx_qtbSD_Mgi9_2vywLFJPAJr5v8c";
        break;
        // CAMPAÑA COMPRADORES EN EL EXTERIOR CHIPICHAPE(cali) new
      case "1129816413024008":
        spreadsheetId = "12uQJpapPNVaiAbFRCeLG6vlEwTu2FIu1-9ofyKDKj28";
        break;
        // BREZZA (Bogotá) new
      case "1690559946012269":
        spreadsheetId = "1F-54Y-m9oB6yGoBWBjdRZR0GlKyYr6WoYOURLGI4E1g";
        break;
        //RESERVA DE LLANO GRANDE (Pereira) new
      case "2620317548403714":
        spreadsheetId = "1N3iXF_PipkTsnTW2CWEbzGgmynfMsHWK_53iRjrepHg";
        break; 
       // Limonar 01 OCT 2026 / Ajustado ID de sheets 14 de oct
      case "1116331730857484":
        spreadsheetId = "11IeS-8Djac5ETvITrnVwDRLf9AQSlM18FVrEbhvA3ZM";
        break;
      // TERRA CASTILLO 02 OCT 2026
      case "1476532834404709":
        spreadsheetId = "1hdpoPEs894aisaxvm9q3m0BN6kEsfqPfaRo5DNA2Wqc";
        break;
       // MORATTI 05 OCT 2026
      case "2196438881086879":
        spreadsheetId = "1L9hNy18UWj7ZalhD-cbfnejEcPY2lmmn0XpPEXYVBwA";
        break;  
    }

    // --- obtener lead details ---
    const leadUrl = `https://graph.facebook.com/v22.0/${leadgenId}?fields=created_time,ad_name,adset_name,campaign_name,field_data&access_token=${accessPageToken}`;
    let leadDetails;
    try {
      const fbLeadResp = await axios.get(leadUrl);

      console.log(
        "[META] FIELD DATA:",
        JSON.stringify(fbLeadResp.data.field_data, null, 2),
      );

      leadDetails = fbLeadResp.data;

      console.log("[META] Datos publicitarios disponibles:", {
        campaignName: leadDetails.campaign_name || null,
        adName: leadDetails.ad_name || null,
        adsetName: leadDetails.adset_name || null,
        adId: leadDetails.ad_id || null,
      });
    } catch (err) {
      console.error(
        "Error al consultar lead:",
        err.response?.data || err.message,
      );
      return;
    }

    // --- Parsear campos de Meta ---
    let firstName = "NO DATA";
    let lastName = "NO DATA";
    let phoneNumber = "NO DATA";
    let email = "NO DATA";

    let occupation = "NO DATA";
    let ahorro = "NO DATA";
    let ingresos = "NO DATA";

    let campaignName = leadDetails?.campaign_name || "NO DATA";

    const normalizeFieldName = (name = "") =>
      name
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .trim();

    const isMetaDummy = (value) =>
      typeof value === "string" && /<test lead:.*>/i.test(value.trim());

    const getFieldValue = (field) => {
      const values = Array.isArray(field.values) ? field.values : [];

      return values
        .filter(
          (value) =>
            typeof value === "string" &&
            value.trim() !== "" &&
            !isMetaDummy(value),
        )
        .join(", ")
        .trim();
    };

    if (Array.isArray(leadDetails?.field_data)) {
      for (const field of leadDetails.field_data) {
        const rawName = field.name || "";
        const name = normalizeFieldName(rawName);
        const value = getFieldValue(field);

        // Ignorar valores ficticios generados por Meta
        if (!value) {
          console.log(`[META] Campo de prueba o vacío: ${name}`);
          continue;
        }

        // Nombre completo
        if (name === "full_name") {
          const parts = value.split(/\s+/).filter(Boolean);

          if (parts.length > 1) {
            firstName = parts.slice(0, -1).join(" ");
            lastName = parts[parts.length - 1];
          } else {
            firstName = value;
          }

          continue;
        }

        // Nombre y apellido cuando Meta los entrega separados
        if (name === "first_name") {
          firstName = value;
          continue;
        }

        if (name === "last_name") {
          lastName = value;
          continue;
        }

        // Teléfono
        if (
          name.includes("phone") ||
          name.includes("tel") ||
          name.includes("celular") ||
          name.includes("mobile")
        ) {
          const digits = value.replace(/\D/g, "");

          if (digits.length >= 7) {
            phoneNumber = digits;
          }

          continue;
        }

        // Correo
        if (name.includes("email") || name.includes("mail")) {
          email = value;
          continue;
        }

        // Ocupación
        if (name.includes("ocup")) {
          occupation = value;
          continue;
        }

        // Ahorro para compra
        if (name.includes("ahorro") || name.includes("compra")) {
          ahorro = value;
          continue;
        }

        // Ingresos mensuales
        if (name.includes("mensuales") || name.includes("ing")) {
          ingresos = value;
        }
      }
    }

    console.log("[META] Campos procesados:", {
      nombreEncontrado: firstName !== "NO DATA",
      apellidoEncontrado: lastName !== "NO DATA",
      telefonoEncontrado: phoneNumber !== "NO DATA",
      correoEncontrado: email !== "NO DATA",
      ocupacionEncontrada: occupation !== "NO DATA",
      ingresosEncontrados: ingresos !== "NO DATA",
      ahorroEncontrado: ahorro !== "NO DATA",
      campanaEncontrada: campaignName !== "NO DATA",
    });

    const date = new Date(leadDetails?.created_time || Date.now());
    const formattedDate = `${String(date.getDate()).padStart(2, "0")}/${String(date.getMonth() + 1).padStart(2, "0")}/${date.getFullYear()}`;

    const cleanPhone = /^\d{7,15}$/.test(phoneNumber) ? phoneNumber : "";

    // Construir el arreglo de valores a insertar
    const values = [
      [
        leadgenId,
        formattedDate,
        "New",
        firstName,
        lastName,
        `'${cleanPhone}'`, // ← usa el teléfono limpio
        email,
        "Facebook ADS",
        campaignName,
        ingresos,
        ahorro,
        occupation,
      ],
    ];

    let sincoResponse = null;

    try {
      const auth = await authorize();

      console.log("[GOOGLE AUTH] Autenticación de Google correcta");

      await appendValues(auth, values, spreadsheetId);

      console.log("Datos guardados correctamente en Sheets:", values);
    } catch (err) {
      console.error(
        "Error guardando en Sheets:",
        err.response?.data || err.message,
      );
    }

    //ENVIO A SINCO
    try {
      const token = await getSincoToken();

      if (
        !phoneNumber ||
        phoneNumber === "NO DATA" ||
        !/^\d{7,15}$/.test(phoneNumber)
      ) {
        console.warn("[SINCO] Lead sin teléfono, no se envía");
      } else {
        const sincoBody = {
          idContexto: 237,
          idCategoria: 0,
          nombres: firstName,
          apellidos: lastName,
          correo: email !== "NO DATA" ? email : "noemail@lead.com",
          celular: phoneNumber,
          origenInformacion: 10,
          numeroIdentificacion: "0",
          tipoIdentificacion: "CC",
          sexo: "O",
          haAutorizadoManejoInformacion: true,
          fechaNacimiento: "1990-01-01T00:00:00.000Z",
          haAutorizadoEnvioCorreo: true,
          haAutorizadoEnvioSMS: false,
          haAutorizadoEnvioWhatsApp: false,
          haAutorizadoLlamada: false,
          idMedioPublicitario: 11,
          idProceso: 7,
        };

        const res = await client.post(
          `${SINCO_BASE_URL}/V3/CBRClientes/API/SalaVentas/Externo/Visitas/PorProceso`,
          sincoBody,
          {
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
              Authorization: token,
              "User-Agent": "PostmanRuntime/7.32.3",
            },
            timeout: 15000,
            maxRedirects: 5,
            validateStatus: () => true,
          },
        );

        console.log("[SINCO] AXIOS status:", res.status, "| data:", res.data);

        console.log("[SINCO] status:", res.status);
        console.log("[SINCO] response:", res.data);

        if (![200, 201, 409].includes(res.status)) {
          console.error("[SINCO] ERROR:", {
            status: res.status,
            data: res.data,
            lead: {
              nombre: firstName,
              telefono: phoneNumber,
              email,
            },
          });
        }

        sincoResponse = res.data;
      }
    } catch (err) {
      console.error("[SINCO] error:", err.response?.data || err.message);
    }

    console.log("=== RESULTADO FINAL ===");
    console.log({
      leadgenId,
      nombre: firstName,
      telefono: phoneNumber,
      email,
      sinco: sincoResponse,
    });

    // siempre responde 200 a Meta
    return;
  } catch (err) {
    console.error("Error general en /insertData:", err);
    return;
  }
});

// --- GET para verificación de webhook ---
app.get("/insertData", async (req, res) => {
  const mode = req.query["hub.mode"];
  const token = req.query["hub.verify_token"];
  const challenge = req.query["hub.challenge"];
  if (mode === "subscribe" && token === META_VERIFY_TOKEN) {
    return res.status(200).send(challenge);
  } else {
    return res.status(403).json("Forbidden - Invalid token");
  }
});

//  SINCO – Proxy PQRS Galias

const SINCO_BASE_URL =
  process.env.SINCO_BASE_URL ||
  "https://www4.sincoerp.com/SincoConsGalias_Nueva";

const SINCO_API_USER = process.env.SINCO_API_USER;
const SINCO_API_PASSWORD = process.env.SINCO_API_PASSWORD;

// Cache del token en memoria (dura ~24h igual que SINCO)
let sincoTokenCache = { token: null, expiresAt: 0 };

async function getSincoToken() {
  const now = Date.now();

  if (sincoTokenCache.token && now < sincoTokenCache.expiresAt) {
    console.log("[SINCO] Token desde caché");
    return sincoTokenCache.token;
  }

  console.log("[SINCO] Obteniendo nuevo token...");

  if (!SINCO_API_USER || !SINCO_API_PASSWORD) {
    throw new Error("SINCO no está configurado: faltan credenciales");
  }

  const SINCO_AUTH_BODY = {
    NomUsuario: SINCO_API_USER,
    ClaveUsuario: SINCO_API_PASSWORD,
  };

  // 1. AUTH
  const res1 = await client.post(
    `${SINCO_BASE_URL}/V3/API/Auth/Usuario`,
    SINCO_AUTH_BODY,
    {
      headers: { "Content-Type": "application/json" },
      timeout: 10000,
      validateStatus: () => true,
    },
  );

  const accessToken = res1.data?.access_token;

  if (!accessToken) {
    throw new Error("SINCO auth falló: " + JSON.stringify(res1.data));
  }

  if (!accessToken) {
    throw new Error("SINCO sin access_token");
  }

  const bd = res1.data?.data?.bdIngreso?.[0] || 1;

  // 2. SESIÓN (ACTIVA CONTEXTO REAL)
  const res2 = await client.get(
    `${SINCO_BASE_URL}/V3/API/Auth/Sesion/IniciarMovil/${bd}/Empresa/1/Sucursal/0`,
    {
      headers: {
        Authorization: `Bearer ${accessToken}`,
      },
      timeout: 10000,
      validateStatus: () => true,
    },
  );

  console.log("[SINCO] Sesión status:", res2.status);

  if (res2.status !== 200) {
    throw new Error("SINCO iniciar sesión falló: " + JSON.stringify(res2.data));
  }

  const finalToken = res2.data?.access_token;

  if (!finalToken) {
    throw new Error("SINCO sin access_token de sesión");
  }

  sincoTokenCache = {
    token: `Bearer ${finalToken}`,
    expiresAt: now + 55 * 60 * 1000, // 55 minutos de caché para evitar expiración
  };

  console.log("[SINCO] Token OK");

  return sincoTokenCache.token;
}

// POST /sinco/visita
// Crea la visita/caso en SINCO y devuelve el nroCaso al frontend
app.post("/sinco/visita", upload.single("archivo"), async (req, res) => {
  console.log("[SINCO] POST /sinco/visita body:", JSON.stringify(req.body));

  console.log("==========================");
  console.log("NUEVA SOLICITUD");
  console.log("Fecha:", new Date());
  console.log("Documento:", req.body.numeroIdentificacion);
  console.log("==========================");

  const requestId = `${Date.now()}-${req.body.numeroIdentificacion}`;
  console.log("REQUEST ID:", requestId);

  try {
    const token = await getSincoToken();

    console.log(
      "[SINCO] URL POST:",
      `${SINCO_BASE_URL}/V3/CBRClientes/API/SalaVentas/Externo/Visitas/PorProceso`,
    );
    console.log("[SINCO] Token obtenido correctamente");

    let datosCliente = null;

    try {
      const resCliente = await client.get(
        `${SINCO_BASE_URL}/V3/CBRClientes/API/VisitaPorVisitante/numeroIdentificacion/${req.body.numeroIdentificacion}`,
        {
          headers: { Authorization: token },
        },
      );

      datosCliente = resCliente.data;
    } catch (e) {
      console.log("[SINCO] Cliente no encontrado");
    }

    const bodyFinal = {
      ...req.body,
      idCategoria: 0,
    };

    console.log("[SINCO] Solicitud de visita preparada");

    console.log(`[${requestId}] CREANDO VISITA EN SINCO...`);
    const response = await client.post(
      `${SINCO_BASE_URL}/V3/CBRClientes/API/SalaVentas​/Externo​/Visitas/PorProceso`,
      bodyFinal,
      {
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          Authorization: token,
        },
        timeout: 15000,
        validateStatus: () => true,
      },
    );
    console.log(`[${requestId}] RESPUESTA SINCO`);
    console.log(`[${requestId}] STATUS:`, response.status);
    console.log(`[${requestId}] DATA:`, response.data);

    const data =
      typeof response.data === "string"
        ? response.data
        : JSON.stringify(response.data);

    console.log("[SINCO] AXIOS status:", response.status);
    console.log("[SINCO] AXIOS data:", data);

    // Soporta respuestas de SINCO: "creada" o "asociada"
    const match = data.match(/visita (creada|asociada) id:\s*(\d+)/i);
    const idVisita = match ? match[2] : null;

    if (!idVisita) {
      return res.status(500).json({
        error: "No se pudo extraer idVisita",
        raw: data,
      });
    }

    if (![200, 201, 409].includes(response.status)) {
      return res.status(response.status).json({
        error: "Error al crear visita en SINCO",
        sinco_status: response.status,
        sinco_data: data,
      });
    }

    // Consultar el caso para obtener nroCaso y datos completos
    const idProceso = req.body.idProceso || 7;
    const resCaso = await client.get(
      `${SINCO_BASE_URL}/V3/CBRClientes/API/SalaVentas/Externo/Visitas/IdVisita/${idVisita}/IdProceso/${idProceso}`,
      {
        headers: { "Content-Type": "application/json", Authorization: token },
        timeout: 10000,
        validateStatus: () => true,
      },
    );

    console.log(
      `[${requestId}] Consultar caso status:`,
      resCaso.status,
      "| data:",
      JSON.stringify(resCaso.data),
    );

    const sincoData = resCaso.data || {};

    // MAGICCC
    const macro = sincoData.macroproyecto || "";
    const proyecto = sincoData.proyecto || "";
    const unidad = sincoData.unidad || "";

    console.log("[SINCO] Macro:", macro);
    console.log("[SINCO] Proyecto:", proyecto);
    console.log("[SINCO] Unidad:", unidad);

    //Guardar lead en MySQL
    try {
      await db.execute(
        `INSERT INTO leads_sinco 
        (nombres, apellidos, correo, celular, numeroIdentificacion, idVisita, idProceso)
        VALUES (?, ?, ?, ?, ?, ?, ?)`,
        [
          req.body.nombres,
          req.body.apellidos,
          req.body.correo,
          req.body.celular,
          req.body.numeroIdentificacion,
          idVisita,
          req.body.idProceso || 7,
        ],
      );

      console.log("[DB] Lead guardado");
    } catch (dbError) {
      console.error("[DB] Error:", dbError.message);
    }

    console.log(`[${requestId}] FINALIZADO OK`);

    // Devolver todo al frontend
    return res.status(200).json({
      ok: true,
      idVisita,
      macro,
      proyecto,
      unidad,
      caso: resCaso.status === 200 ? resCaso.data : null,
    });
  } catch (err) {
    console.error(
      `[${requestId}] Error en /sinco/visita:`,
      err.response?.data || err.message,
    );

    console.log(`[${requestId}] FINALIZADO ERROR`);
    return res.status(500).json({ error: err.message });
  }
});

app.get("/sinco/tipologias", async (req, res) => {
  try {
    const token = await getSincoToken();

    const response = await client.get(
      `${SINCO_BASE_URL}/V3/CBRClientes/API/SalaVentas​/Externo​/Visitas/ObtenerTipologiasAdjuntos`,
      {
        headers: { Authorization: token },
      },
    );

    res.json(response.data);
  } catch (err) {
    console.error(err.response?.data || err.message);
    res.status(500).json({ error: "Error obteniendo tipologías" });
  }
});

app.post("/sinco/adjunto", upload.single("archivo"), async (req, res) => {
  try {
    const token = await getSincoToken();

    const { idVisita } = req.body;
    const file = req.file;

    if (!file) {
      return res.status(400).json({ error: "Archivo requerido" });
    }

    // convertir a base64
    const archivoBase64 = file.buffer.toString("base64");

    // obtener tipologías dinámicamente
    const tipologiasRes = await client.get(
      `${SINCO_BASE_URL}/V3/CBRClientes/API/SalaVentas​/Externo​/Visitas/ObtenerTipologiasAdjuntos`,
      {
        headers: { Authorization: token },
      },
    );

    const tipologia =
      tipologiasRes.data.find((t) => t.activo) || tipologiasRes.data[0];

    const idTipologia = tipologia?.id;

    console.log("TIPOLOGIA:", idTipologia);

    const extension = file.originalname.split(".").pop().toLowerCase();

    const mimeMap = {
      pdf: "application/pdf",
      jpg: "image/jpeg",
      jpeg: "image/jpeg",
      png: "image/png",
      doc: "application/msword",
      docx: "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    };

    const tipoArchivo = mimeMap[extension];

    if (!tipoArchivo) {
      return res.status(400).json({
        error: `Tipo de archivo no permitido: .${extension}`,
      });
    }

    const body = {
      idVisita: parseInt(idVisita),
      idUsuarioCreacion: 0,
      archivo: archivoBase64,
      observacion: "Adjunto desde formulario web",
      idTipologia: idTipologia,
      nombreArchivo: file.originalname,
      tipoArchivo: tipoArchivo,
    };

    const response = await client.post(
      `${SINCO_BASE_URL}/V3/CBRClientes/API/SalaVentas​/Externo​/Visitas/NuevoAdjuntoVisita`,
      body,
      {
        headers: {
          Authorization: token,
          "Content-Type": "application/json",
        },
      },
    );

    res.json(response.data);
  } catch (err) {
    console.error("ERROR ADJUNTO SINCO");
    console.error("STATUS:", err.response?.status);
    console.error("DATA:", err.response?.data);

    res.status(500).json({
      error: "Error adjuntando archivo",
      detalle: err.response?.data || err.message,
    });
  }
});

app.post("/sinco/observacion", async (req, res) => {
  try {
    const token = await getSincoToken();

    const { idVisita, observacion } = req.body;

    const response = await client.post(
      `${SINCO_BASE_URL}/V3/CBRClientes/API/SalaVentas​/Externo​/Visitas/EnviarObservacionVisita`,
      {
        idVisita,
        observacion,
      },
      {
        headers: {
          Authorization: token,
          "Content-Type": "application/json",
        },
      },
    );

    res.json(response.data);
  } catch (err) {
    console.error(err.response?.data || err.message);
    res.status(500).json({ error: "Error enviando observación" });
  }
});

app.get("/sinco/reset", (req, res) => {
  sincoTokenCache = { token: null, expiresAt: 0 };
  res.json({ ok: true });
});

// Cache en memoria (5 minutos)
let macrosCache = {
  data: null,
  expiresAt: 0,
};

app.get("/sinco/macros", async (req, res) => {
  const start = Date.now();

  try {
    // 1) CACHE HIT
    if (macrosCache.data && Date.now() < macrosCache.expiresAt) {
      console.log("[SINCO] MACROS desde cache");
      return res.json(macrosCache.data);
    }

    // 2) TOKEN
    const token = await getSincoToken();

    console.log("[SINCO] Token obtenido para consulta de macros");
    console.log("[SINCO] Consultando macroproyectos con sesión autenticada");
    // 3) REQUEST A SINCO (con timeout)
    const response = await client.get(
      `${SINCO_BASE_URL}/V3/CBRClientes/API/Macroproyectos/Basica`,
      {
        headers: {
          Authorization: token,
          "Content-Type": "application/json",
        },
        timeout: 10000, // corta a los 10s
        validateStatus: () => true,
      },
    );

    const duration = Date.now() - start;
    console.log(
      `[SINCO] MACROS status: ${response.status} | tiempo: ${duration}ms`,
    );

    // 4) ERROR DE SINCO
    if (response.status !== 200) {
      return res.status(response.status).json({
        error: "SINCO respondió error",
        sinco_status: response.status,
        sinco_data: response.data,
      });
    }

    // 5) GUARDAR EN CACHE
    macrosCache = {
      data: response.data,
      expiresAt: Date.now() + 5 * 60 * 1000,
    };

    // 6) RESPUESTA
    return res.json(response.data);
  } catch (err) {
    const duration = Date.now() - start;

    // TIMEOUT
    if (err.code === "ECONNABORTED") {
      console.error(`[SINCO] TIMEOUT macros (${duration}ms)`);
      return res.status(504).json({ error: "SINCO timeout" });
    }

    console.error("[SINCO] macros error:", err.message);
    return res.status(500).json({
      error: "Error obteniendo macroproyectos",
      detalle: err.message,
    });
  }
});

app.get("/sinco/proyectos/:idMacro", async (req, res) => {
  try {
    const token = await getSincoToken();

    const response = await client.get(
      `${SINCO_BASE_URL}/V3/CBRClientes/API/Proyectos/${req.params.idMacro}`,
      { headers: { Authorization: token } },
    );

    res.json(response.data);
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: "Error proyectos" });
  }
});

app.get("/sinco/unidades/:idProyecto", async (req, res) => {
  try {
    const token = await getSincoToken();

    const response = await client.get(
      `${SINCO_BASE_URL}/V3/CBRClientes/API/Unidades/PorProyecto/${req.params.idProyecto}`,
      { headers: { Authorization: token } },
    );

    const filtradas = response.data.filter((u) => u.estado === "VENDIDO");

    res.json(filtradas);
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: "Error unidades" });
  }
});

app.get("/sinco/cliente-completo/:identificacion", async (req, res) => {
  try {
    const identificacion = req.params.identificacion;
    const token = await getSincoToken();

    let comprador = {};
    let propiedad = null;

    // 1. DATOS DEL COMPRADOR
    try {
      const resComprador = await client.get(
        `${SINCO_BASE_URL}/V3/CBRClientes/API/Compradores/numeroIdentificacion/${identificacion}`,
        {
          headers: { Authorization: token },
          timeout: 10000,
          validateStatus: () => true,
        },
      );

      comprador = resComprador?.data || {};
    } catch (e) {
      console.log("[SINCO] Comprador no encontrado");
    }

    let propiedades = [];

    // 2. VENTAS (FUENTE REAL)
    try {
      const resVentas = await client.get(
        `${SINCO_BASE_URL}/V3/CBRClientes/API/Ventas/NumeroIdentificacion/${identificacion}`,
        {
          headers: { Authorization: token },
          timeout: 10000,
          validateStatus: () => true,
        },
      );

      const ventas = Array.isArray(resVentas?.data?.agrupacionesComprador)
        ? resVentas.data.agrupacionesComprador
        : [];

      // MULTIPROPIEDAD
      propiedades = ventas.flatMap((v) =>
        (v.unidadesAgrupacion || []).map((u) => ({
          macroproyecto: v.nombreMacroProyecto,
          proyecto: v.nombreProyectoAgrupacion,
          unidad: u.nombre,
        })),
      );

      console.log("[SINCO] PROPIEDADES:", propiedades);
    } catch (e) {
      console.log("[SINCO] Sin ventas");
    }

    // 3. RESPUESTA UNIFICADA
    const result = {
      nombre: comprador.nombreCompleto || comprador.nombre || "",
      correo: comprador.correo || comprador.emailComprador || "",
      celular: comprador.celular || comprador.telefono || "",

      propiedades: propiedades,
    };

    return res.json(result);
  } catch (err) {
    console.error("[SINCO] Error general:", err.response?.data || err.message);
    return res.status(500).json({ error: "Error consultando cliente" });
  }
});

app.get("/sinco/proyectos", async (req, res) => {
  try {
    const { identificacion } = req.query;

    if (!identificacion) {
      return res.status(400).json({ error: "Falta identificacion" });
    }

    const token = await getSincoToken();

    const response = await client.get(
      `${SINCO_BASE_URL}/V3/CBRClientes/API/VisitaPorVisitante/numeroIdentificacion/${identificacion}`,
      {
        headers: {
          "Content-Type": "application/json",
          Authorization: token,
        },
      },
    );

    const data = response.data;

    const visitas = data.visitaVisitante || [];

    const visita = visitas.length
      ? visitas.sort((a, b) => b.idVisita - a.idVisita)[0]
      : null;

    if (!visita) return res.json([]);

    return res.json([
      {
        nombre: visita.proyecto,
        macro: visita.macroproyecto,
        unidad: visita.unidad,
      },
    ]);
  } catch (err) {
    console.error(
      "[SINCO] proyectos error:",
      err.response?.data || err.message,
    );
    return res.status(500).json({ error: "Error obteniendo proyectos" });
  }
});

// GET /sinco/debug/proyectos
app.get("/sinco/debug/proyectos", async (req, res) => {
  try {
    const token = await getSincoToken();

    const response = await client.get(
      `${SINCO_BASE_URL}/V3/API/Cliente/Proyectos`,
      {
        headers: {
          "Content-Type": "application/json",
          Authorization: token,
        },
        validateStatus: () => true,
      },
    );

    console.log("[SINCO] Proyectos status:", response.status);
    console.log("[SINCO] Proyectos data:", JSON.stringify(response.data));

    return res.status(response.status).json(response.data);
  } catch (err) {
    console.error(
      "[SINCO] Error proyectos:",
      err.response?.data || err.message,
    );
    return res.status(500).json({ error: err.message });
  }
});

// GET /sinco/categorias
// Devuelve las categorías del proceso (por defecto proceso 7)
app.get("/sinco/categorias", async (req, res) => {
  try {
    const token = await getSincoToken();
    const idProceso = req.query.idProceso || 7;

    const response = await client.get(
      `${SINCO_BASE_URL}/V3/CBRClientes/API/SalaVentas/Externo/Visitas/Categorias/PorProceso/${idProceso}`,
      {
        headers: {
          "Content-Type": "application/json",
          Authorization: token,
          Accept: "application/json",
        },
        validateStatus: () => true,
      },
    );

    console.log("[SINCO] Categorias status:", response.status);
    console.log("[SINCO] Categorias data:", JSON.stringify(response.data));

    if (response.status !== 200) {
      return res.status(response.status).json({
        error: "Error al obtener categorías",
        sinco_status: response.status,
        sinco_data: response.data,
      });
    }

    return res.json(response.data);
  } catch (err) {
    console.error(
      "[SINCO] Error en /sinco/categorias:",
      err.response?.data || err.message,
    );
    return res.status(500).json({
      error: err.message,
    });
  }
});

app.listen(port, () => {
  console.log(`Servidor escuchando en http://localhost:${port}`);
});

