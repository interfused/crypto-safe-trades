class ExchangeCredentials {
    id: string;
    userId: string;
    exchangeId: string;
    exchangeName: string;
    apiKey: string;
    apiSecret: string;
    
    constructor(){
      this.id = "";
      this.userId = "";
      this.exchangeId = "";
      this.exchangeName = "";
      this.apiKey = "";
      this.apiSecret = "";
    }
}

export default ExchangeCredentials