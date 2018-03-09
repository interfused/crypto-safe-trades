// Accessing the Service that we just created

var PositionService = require('../services/positions.service')

// Saving the context of this module inside the _the variable

_this = this


// Async Controller function to get the Position List

exports.getPositions = async function(req, res, next){

    // Check the existence of the query parameters, If the exists doesn't exists assign a default value
    
    var page = req.query.page ? req.query.page : 1
    var limit = req.query.limit ? req.query.limit : 10; 

    try{
    
        var positions = await PositionService.getPositions({}, page, limit)
        
        // Return the positions list with the appropriate HTTP Status Code and Message.
        
        return res.status(200).json({status: 200, data: positions, message: "Succesfully Positions Recieved"});
        
    }catch(e){
        
        //Return an Error Response Message with Code and the Error Message.
        
        return res.status(400).json({status: 400, message: e.message});
        
    }
}

exports.createPosition = async function(req, res, next){

    // Req.Body contains the form submit values.

    var position = {
        trade_id: req.body.trade_id,
        exchange: req.body.exchange,
        currency: req.body.currency,
        base_currency: req.body.base_currency,
        purchased_qty: req.body.purchased_qty,
        purchase_price: req.body.purchase_price,
        status: req.body.status
    }

    try{
        
        // Calling the Service function with the new object from the Request Body
    
        var createdPosition = await PositionService.createPosition(position)
        return res.status(201).json({status: 201, data: createdPosition, message: "Succesfully Created Position"})
    }catch(e){
        
        //Return an Error Response Message with Code and the Error Message.
        
        return res.status(400).json({status: 400, message: "Position Creation was Unsuccesfull"})
    }
}

exports.updatePosition = async function(req, res, next){

    // Id is necessary for the update

    if(!req.body._id){
        return res.status(400).json({status: 400., message: "Id must be present"})
    }

    var id = req.body._id;

    console.log(req.body)

    var position = {
        id,
        trade_id: req.body.trade_id ? req.body.trade_id : null,
        exchange: req.body.exchange ? req.body.exchange : null,
        currency: req.body.currency ? req.body.currency : null,
        base_currency: req.body.base_currency ? req.body.base_currency : null,
        purchased_qty: req.body.purchased_qty ? req.body.purchased_qty : null,
        purchase_price: req.body.purchase_price ? req.body.purchase_price : null,
        status: req.body.status ? req.body.status : null
    }

    try{
        var updatedPosition = await PositionService.updatePosition(position)
        return res.status(200).json({status: 200, data: updatedPosition, message: "Succesfully Updated Position"})
    }catch(e){
        return res.status(400).json({status: 400., message: e.message})
    }
}

exports.removePosition = async function(req, res, next){

    var id = req.params.id;

    try{
        var deleted = await PositionService.deletePosition(id)
        return res.status(200).json({status:200, message: "Succesfully Position Deleted"})
    }catch(e){
        return res.status(400).json({status: 400, message: e.message})
    }

}