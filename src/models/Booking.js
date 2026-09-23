const mongoose = require("mongoose");

const bookingSchema = new mongoose.Schema({

    guestName: {
        type: String,
        required: true,
        trim: true
    },

    guestEmail: {
        type: String,
        required: true,
        lowercase: true,
        trim: true
    },

    room: {
        type: mongoose.Schema.Types.ObjectId,
        ref: "Room",
        required: true
    },

    checkIn: {
        type: Date,
        required: true
    },

    checkOut: {
        type: Date,
        required: true
    },

    guests: {
        type: Number,
        required: true,
        min: 1
    },

    totalAmount: {
        type: Number,
        required: true,
        min: 0
    },

    status: {
        type: String,
        enum: ["CONFIRMED", "CANCELLED"],
        default: "CONFIRMED"
    }

}, {
    timestamps: true
});

module.exports = mongoose.model("Booking", bookingSchema);
