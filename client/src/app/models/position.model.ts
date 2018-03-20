class Position {
    id: string;
    user_id: string;
    trade_id: string;
    exchange_id: string;
    trade_pair: string;
    currency: string;
    base_currency: string;
    qty: string;
    price: string;
    date: Date;
    fully_closed: string;
    
    constructor(){
      this.id = "";
      this.user_id = "";
      this.trade_id = "";
      this.exchange_id = "";
      this.trade_pair = "";
      this.currency = "";
      this.base_currency = "";
      this.qty = "";
      this.price = "";
      this.date= new Date();
      this.fully_closed = "";  
    }
}

export default Position