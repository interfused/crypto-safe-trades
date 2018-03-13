class Position {
    trade_id: string;
    exchange: string;
    trade_pair: string;
    currency: string;
    base_currency: string;
    purchased_qty: string;
    purchase_price: string;
    date: Date;
    status: string;
    
    constructor(){
      this.trade_id = "";
      this.exchange = "";
      this.trade_pair = "";
      this.currency = "";
      this.base_currency = "";
      this.purchased_qty = "";
      this.purchase_price = "";
      this.date= new Date();
      this.status = "";  
    }
}

export default Position