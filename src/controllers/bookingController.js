const {
    createBooking,
    cancelBooking
} = require("../services/bookingService");


// Create booking
const bookRoom = async (req, res) => {

    try {

        const booking = await createBooking(req.body);

        res.status(201).json({

            success: true,
            message: "Room booked successfully",
            booking

        });

    } catch (error) {

        res.status(400).json({

            success: false,
            message: error.message

        });

    }
};


// Cancel booking
const cancelRoomBooking = async (req, res) => {

    try {

        const booking = await cancelBooking(
            req.params.id
        );

        res.status(200).json({

            success: true,
            message: "Booking cancelled successfully",
            booking

        });

    } catch (error) {

        res.status(400).json({

            success: false,
            message: error.message

        });

    }
};


module.exports = {
    bookRoom,
    cancelRoomBooking
};
