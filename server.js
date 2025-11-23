 import express from "express";
import axios from "axios";

const app = express();

// ---- GANTI TOKEN BOT KAMU DI SINI ----
const BOT_TOKEN = "8489516593:AAFQv2fOZUZuiYU2yNjKaimdj4cwYTLqhKE"; 
// --------------------------------------

app.get("/api/bot", async (req, res) => {
  const target = req.query.target;
  const message = req.query.message;

  if (!target || !message) {
    return res.json({
      success: false,
      message: "Parameter ?target= & ?message= wajib diisi"
    });
  }

  try {
    const url = `https://api.telegram.org/bot${BOT_TOKEN}/sendMessage`;
    const send = await axios.post(url, {
      chat_id: target,
      text: message
    });

    res.json({
      success: true,
      result: send.data
    });

  } catch (err) {
    res.json({
      success: false,
      error: err.response?.data || err.message
    });
  }
});

app.listen(3000, () => {
  console.log("API ready! http://daffa-dev.my.id/api/bot");
});

