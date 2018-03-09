var express = require('express')

var router = express.Router()
var positions = require('./api/positions.route')


router.use('/positions', positions);


module.exports = router;