const express = require("express");
const cors = require("cors");

const roomRoutes = require("./routes/roomRoutes");
const bookingRoutes = require("./routes/bookingRoutes");

const app = express();

app.use(cors());

app.use(express.json());


// Health check
app.get("/health", (req, res) => {

    res.status(200).json({

        status: "OK",
        message: "Hotel backend is running"

    });

});


// API routes
app.use("/api/rooms", roomRoutes);

app.use("/api/bookings", bookingRoutes);


module.exports = app;
