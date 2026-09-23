const express = require("express");

const router = express.Router();

const {
    getRooms,
    addRoom
} = require("../controllers/roomController");


// Get rooms
router.get("/", getRooms);


// Add room
router.post("/", addRoom);


module.exports = router;
