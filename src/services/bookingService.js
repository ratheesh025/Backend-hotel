const Room = require("../models/Room");
const Booking = require("../models/Booking");

// Calculate the number of nights
function calculateNights(checkIn, checkOut) {

    const start = new Date(checkIn);
    const end = new Date(checkOut);

    if (
        Number.isNaN(start.getTime()) ||
        Number.isNaN(end.getTime())
    ) {
        throw new Error("Invalid booking dates");
    }

    const startDay = Date.UTC(
        start.getUTCFullYear(),
        start.getUTCMonth(),
        start.getUTCDate()
    );

    const endDay = Date.UTC(
        end.getUTCFullYear(),
        end.getUTCMonth(),
        end.getUTCDate()
    );

    const nights = (endDay - startDay) / 86400000;

    if (nights <= 0) {
        throw new Error(
            "Check-out date must be after check-in date"
        );
    }

    return nights;
}


// Create a new booking
async function createBooking({
    guestName,
    guestEmail,
    roomId,
    checkIn,
    checkOut,
    guests
}) {

    if (
        !guestName ||
        !guestEmail ||
        !roomId ||
        !checkIn ||
        !checkOut ||
        !guests
    ) {
        throw new Error("All booking fields are required");
    }

    if (!Number.isInteger(guests) || guests < 1) {
        throw new Error("Invalid number of guests");
    }

    const nights = calculateNights(checkIn, checkOut);

    // Find the room
    const room = await Room.findOne({
        _id: roomId,
        isActive: true
    });

    if (!room) {
        throw new Error("Room not found or inactive");
    }

    // Check room capacity
    if (guests > room.capacity) {
        throw new Error(
            "Number of guests exceeds room capacity"
        );
    }

    // Check overlapping bookings
    const existingBooking = await Booking.findOne({

        room: roomId,

        status: "CONFIRMED",

        checkIn: {
            $lt: new Date(checkOut)
        },

        checkOut: {
            $gt: new Date(checkIn)
        }

    });

    if (existingBooking) {
        throw new Error(
            "Room is already booked for the selected dates"
        );
    }

    // Calculate total price
    const totalAmount = room.pricePerNight * nights;

    // Create booking
    const booking = await Booking.create({

        guestName,
        guestEmail,
        room: roomId,
        checkIn,
        checkOut,
        guests,
        totalAmount,
        status: "CONFIRMED"

    });

    return booking;
}


// Cancel a booking
async function cancelBooking(bookingId) {

    const booking = await Booking.findById(bookingId);

    if (!booking) {
        throw new Error("Booking not found");
    }

    if (booking.status === "CANCELLED") {
        throw new Error("Booking is already cancelled");
    }

    booking.status = "CANCELLED";

    await booking.save();

    return booking;
}


module.exports = {
    calculateNights,
    createBooking,
    cancelBooking
};
