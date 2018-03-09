var express = require('express')

var router = express.Router()

// Getting the Position Controller that we just created

var ToDoController = require('../../controllers/positions.controller');


// Map each API to the Controller FUnctions

router.get('/', ToDoController.getPositions)

router.post('/', ToDoController.createPosition)

router.put('/', ToDoController.updatePosition)

router.delete('/:id',ToDoController.removePosition)


// Export the Router

module.exports = router;