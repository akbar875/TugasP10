const express = require('express');
const rateLimit = require('express-rate-limit');

const app = express();
const port = 3780;

app.use(express.json());

// throttling untuk mendelay setiap request 300ms
const throttle = (req, res, next) => {
    setTimeout(next, 300);
};

// Rate Limiting: maks 5 request per menit
const limiter = rateLimit({
    windowMs: 60 * 1000, // Jendela waktu 1 menit
    max: 5, // maksimal 5 request per menit
    message: {
        error: "Terlalu banyak permintaan, coba lagi nanti."
    },
  standardHeaders: true,
  legacyHeaders: false,
});

app.use(throttle);
app.use(limiter);

// Route cek service a berjalan
app.get('/', (req, res) => {
    res.json({ message: 'Service A berjalan!' });
});

// Route klasifikasi untuk menerima data untuk dikirim ke Service B
app.post('/klasifikasi', async (req, res) => {
    const { ph, lembap_udara } = req.body;

    // Validasi input
    if (!ph || !lembap_udara) {
        return res.status(400).json({ error: 'ph dan lembap_udara wajib diisi' });
    }

    try {
        // Kirim request ke Service B
        const response = await fetch('http://103.147.92.134:3880/index011.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ph, lembap_udara })
        });

    const data = await response.json();

    res.json({ input: { ph, lembap_udara }, hasil: data });

    } catch (error) {
        res.status(500).json({ error: 'Gagal konek ke Service B', detail: error.message });
    }
});

// Menjalankan server
app.listen(port, () => {
    console.log(`Service A berjalan di http://103.147.92.134:${port}`);
});