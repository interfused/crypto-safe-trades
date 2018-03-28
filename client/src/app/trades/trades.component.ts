import { PositionService } from '../services/position.service';
import { BinanceService } from '../services/binance.service';
import  { EXCHANGES }  from '../__mock/mock-exchanges';
import  Position  from '../models/position.model';
import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';


@Component({
  selector: 'app-trades',
  templateUrl: './trades.component.html',
  styleUrls: ['./trades.component.scss']
})
export class TradesComponent implements OnInit {
  
  exchanges = EXCHANGES;
  
  //Declaring the new position Object and initilizing it
  public newPosition: Position = new Position();
  
  //An Empty list for the visible positions list
  positionsList: Position[] = [];
  editPositions: Position[] = [];
  apiExchangeInfoUrl = '';
  tradePairs = [];
  filteredTradePairs = []; //the potential filtered pair list
  baseCurrencies = [];
  ticker24hr = [];
  selectedExchange = '';
  userTradeSymbol = '';
  
  constructor(
    //Private service will be injected into the component by Angular Dependency Injector
    private positionService: PositionService,
    private binanceService: BinanceService,
    private http: HttpClient)
  {
    console.log('constructed');  
  }
  
  ngOnInit() {
    //At component initialization the 
    this.getPositions();
    
    let hasRetrievedPricing = false;
    let hasRetrievedTradePairs = false;
    
    this.binanceService.get24hTicker()
    .subscribe(data => {
      this.ticker24hr = data;
      hasRetrievedPricing = true;
      
      if(hasRetrievedPricing && hasRetrievedTradePairs){
        this.combineArrays();
      }
    });
    
    this.binanceService.getTradePairs()
    .subscribe(data => {
      hasRetrievedTradePairs = true;
      this.tradePairs = data;
      
      this.baseCurrencies = this.tradePairs.map((e) => e.quoteAsset )
      .filter (el => el != '456')
      .filter( this.onlyUniqueBaseCurrencies );          
    });
  }
  
  getPositions(){
    this.userTradeSymbol = '';
    
    this.positionService.getPositions()
    .subscribe(positions => {
        //assign the todolist property to the proper http response
        this.positionsList = positions;
        console.log(positions);
      });
  }
  
  getTotal(price, qty, commission ){
    return Number(price) * Number(qty);
  }
  
  getData(){
    /*
    binance.options({
  APIKEY: 'FgCytlBHSjJhWUN0BUHbfVsjo5V1c8XDpGArYvd025UQED5yK7ykel5zJ1Y3xK6X',
  APISECRET: '3G7tyoU9xhBqXbt27FONa0e6X1TyF58TSdTlJBE0D8POlDscYISx9UoEfAjhOjbN',
  useServerTime: true, // If you get timestamp errors, synchronize to server time at startup
  test: true // If you want to use sandbox mode where orders are simulated
});

    binance.prices((error, ticker) => {
  console.log("prices()", ticker);
  console.log("Price of BTC: ", ticker.BTCUSDT);
});
    */
    let res=this.http.get(this.apiExchangeInfoUrl).map((res: Response)=>res.json());
    console.log("result:",res)
    return res;
  }
  
  onlyUniqueBaseCurrencies(value, index, self) { 
    return self.indexOf(value) === index;
  }
  
  switchBaseCurrency(e){
    console.log('switch base to: ' + e.target.value);
    if(e.target.value == '' ){
      this.filteredTradePairs = this.tradePairs;  
    }else{
      this.filteredTradePairs = this.tradePairs.filter( el => el.quoteAsset == e.target.value  );
    } 
  }
  //COMBINE PRICING AND TRADE PAIR ARRAYS FOR BINANCE
  combineArrays(){
    console.log('COMBINE ARRAYS');
    
    for(let i=0; i<this.tradePairs.length; i++){
      let obj1 = this.tradePairs[i];
      let symbol = this.tradePairs[i]['symbol'];
      
      let symbol24hrData = this.ticker24hr.filter(e => e.symbol === symbol );
      this.tradePairs[i]['ticker24h'] = symbol24hrData[0];
      //combine 
      //this.tradePairs[i] = Object.assign({},obj1,symbol24hrData);
    }
    this.filteredTradePairs = this.tradePairs.filter (el => el.quoteAsset != '456');
    console.log(this.tradePairs);
  }
  
  selectExchange(e:any){
    this.newPosition.exchange_id = e.target.value;
    
    switch(e.target.value){
      case "1":
        this.selectedExchange = 'binance';
        //this.apiExchangeInfoUrl = 'https://api.binance.com/api/v1/exchangeInfo';
        //this.getData();
        /*
        this.binanceService.getInfo()
        .subscribe(positions => {
          //assign the todolist property to the proper http response
          //this.positionsList = positions;
          console.log(positions);
        });
        
        let hasRetrievedPricing = false;
        let hasRetrievedTradePairs = false;
        
        this.http.get('http://dev.interfusedcreative.com/crypto-safe-trades-api/v1/binance/get-24hr-ticker.php').subscribe(data => {
          this.ticker24hr = data;
          console.log('pricing data');
          console.log(this.ticker24hr);
          hasRetrievedPricing = true;
          
          if(hasRetrievedPricing && hasRetrievedTradePairs){
            this.combineArrays();
          }
        });
        */
      
      break;
      
      case "2":
        this.selectedExchange = 'cryptopia';
      break;
      
      default:
        console.log('unfilter');
        this.selectedExchange = '';
      break;
    }
    console.log(this.selectedExchange);
        
  }
  
  selectPositionToOpen(base_asset,secondary_asset){
    //EXAMPLE NEOBTC - Base asset would be BTC Secondary asset would be NEO
    //binance
    let symbol = secondary_asset + base_asset;
    
    this.newPosition.trade_pair = symbol;
    this.newPosition.base_currency = base_asset;
    this.newPosition.currency = secondary_asset;
    
    console.log('open position for: ' + this.selectedExchange);
    this.userTradeSymbol = symbol;
    console.log('symbol: ' + this.userTradeSymbol);
  
  }
  
  //This method will get called on Create button event
  createEntry(){
    console.log('create buy trade for: ' + this.selectedExchange);
    console.dir(this.newPosition); 

    this.positionService.createPosition(this.newPosition)
    .subscribe((res) => {
      this.positionsList.push(res.data);
      this.newPosition = new Position();
      this.getPositions();
    });
  }


}
