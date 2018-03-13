var mongoose = require('mongoose')
var mongoosePaginate = require('mongoose-paginate')

//each position will have a maximum of 'N' quantity
//

var PositionSchma = new mongoose.Schema({
    trade_id: String,
    exchange: String,
    trade_pair: String,
    currency: String,
    base_currency: String,
    purchased_qty: String,
    purchase_price: Number,
    date: Date,
    status: String
})

PositionSchma.plugin(mongoosePaginate)
const Position = mongoose.model('Todo', PositionSchma)

module.exports = Position;