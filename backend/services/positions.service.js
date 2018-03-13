// Gettign the Newly created Mongoose Model we just created 

var PositionModel = require('../models/position.model')

// Saving the context of this module inside the _the variable
_this = this

// Async function to get the To do List

exports.getPositions = async function(query, page, limit){

    // Options setup for the mongoose paginate

    var options = {
        page,
        limit
    }
    
    // Try Catch the awaited promise to handle the error 
    
    try {
        var positions = await PositionModel.paginate(query, options)
        
        // Return the positiond list that was retured by the mongoose promise

        return positions;

    } catch (e) {

        // return a Error message describing the reason 

        throw Error('Error while Paginating Positions')
    }
}

exports.createPosition = async function(position){
    
    // Creating a new Mongoose Object by using the new keyword

    var newPosition = new PositionModel({
        trade_id: position.trade_id,
        trade_pair: position.trade_pair,
        exchange: position.exchange,
        currency: position.currency,
        base_currency: position.base_currency,
        purchased_qty: position.purchased_qty,
        purchase_price: position.purchase_price,
        date: new Date(),
        status: position.status
    })

    try{

        // Saving the Position 

        var savedPosition = await newPosition.save()

        return savedPosition;
    }catch(e){
      
        // return a Error message describing the reason     

        throw Error("Error while Creating Position")
    }
}

exports.updatePosition = async function(position){
    var id = position.id

    try{
        //Find the old Position Object by the Id
    
        var oldPosition = await PositionModel.findById(id);
    }catch(e){
        throw Error("Error occured while Finding the Position")
    }

    // If no old Position Object exists return false

    if(!oldPosition){
        return false;
    }

    console.log(oldPosition)

    //Edit the Position Object

    oldPosition.trade_id = position.trade_id;
    oldPosition.exchange = position.exchange;
    oldPosition.trade_pair = position.trade_pair;
    oldPosition.currency = position.currency;
    oldPosition.base_currency = position.base_currency;
    oldPosition.purchased_qty = position.purchased_qty;
    oldPosition.purchase_price = position.purchase_price;
    oldPosition.status = position.status    


    console.log(oldPosition)

    try{
        var savedPosition = await oldPosition.save()
        return savedPosition;
    }catch(e){
        throw Error("And Error occured while updating the Position");
    }
}

exports.deletePosition = async function(id){
    
    // Delete the Position

    try{
        var deleted = await PositionModel.remove({_id: id})
        if(deleted === 0){
            throw Error("Position Could not be deleted")
        }
        return deleted
    }catch(e){
        throw Error("Error Occured while Deleting the Position")
    }
}