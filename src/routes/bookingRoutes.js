const express = require("express");

const router = express.Router();

const {
    bookRoom,
    cancelRoomBooking
} = require("../controllers/bookingController");


// Create booking
router.post("/", bookRoom);


// Cancel booking
router.patch("/:id/cancel", cancelRoomBooking);


module.exports = router;
