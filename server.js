 import express from "express";
import axios from "axios";

const app = express();
const BOT_TOKEN = "8489516593:AAFQv2fOZUZuiYU2yNjKaimdj4cwYTLqhKE";

app.get("/api/bot", async (req, res) => {
  const { target, message } = req.query;

  if (!target || !message) {
    return res.json({
      status: false,
      message: "Parameter ?target= & ?message= wajib diisi"
    });
  }

  try {
    const url = `https://api.telegram.org/bot${BOT_TOKEN}/sendMessage`;
    const send = await axios.post(url, {
      chat_id: target,
      text: message
    });

    return res.json({
      status: true,
      message: "Pesan berhasil dikirim",
      data: {
        chat_id: target,
        text: message,
        telegram_result: send.data
      }
    });

  } catch (err) {
    return res.json({
      status: false,
      message: "Gagal mengirim pesan",
      error: err.response?.data || err.message
    });
  }
});

app.listen(3000, () => console.log("API Online"));
