const mongoose = require("mongoose");

const roomSchema = new mongoose.Schema({

    roomNumber: {
        type: String,
        required: true,
        unique: true
    },

    roomType: {
        type: String,
        enum: ["Single", "Double", "Deluxe", "Suite"],
        required: true
    },

    pricePerNight: {
        type: Number,
        required: true,
        min: 0
    },

    capacity: {
        type: Number,
        required: true,
        min: 1
    },

    isActive: {
        type: Boolean,
        default: true
    }

}, {
    timestamps: true
});

module.exports = mongoose.model("Room", roomSchema);
