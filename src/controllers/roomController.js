const Room = require("../models/Room");


// Get all active rooms
const getRooms = async (req, res) => {

    try {

        const rooms = await Room.find({
            isActive: true
        });

        res.status(200).json({

            success: true,
            rooms

        });

    } catch (error) {

        res.status(500).json({

            success: false,
            message: error.message

        });

    }
};


// Add a room
const addRoom = async (req, res) => {

    try {

        const room = await Room.create(req.body);

        res.status(201).json({

            success: true,
            room

        });

    } catch (error) {

        res.status(400).json({

            success: false,
            message: error.message

        });

    }
};


module.exports = {
    getRooms,
    addRoom
};
