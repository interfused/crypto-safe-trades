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
  positionsList: Position[];
  editPositions: Position[] = [];
  apiExchangeInfoUrl = '';
  tradePairs = [];
  filteredTradePairs = []; //the potential filtered pair list
  baseCurrencies = [];
  
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
  
  selectExchange(e:any){
    
    switch(e.target.value){
      case "1":
        console.log('binance');
        //this.apiExchangeInfoUrl = 'https://api.binance.com/api/v1/exchangeInfo';
        //this.getData();
        /*
        this.binanceService.getInfo()
        .subscribe(positions => {
          //assign the todolist property to the proper http response
          //this.positionsList = positions;
          console.log(positions);
        });
        */
        this.http.get('http://dev.interfusedcreative.com/crypto-safe-trades-api/v1/binance/get-info.php').subscribe(data => {
          //          console.log(data.symbols);
          this.tradePairs = data['symbols'];
          this.filteredTradePairs = this.tradePairs;          
          console.log('trade pairs');
          console.log(this.tradePairs);
          console.log('///////////');
          this.baseCurrencies = this.tradePairs.map((e) => e.quoteAsset  )
                                .filter (el => el != '456')
                                .filter( this.onlyUniqueBaseCurrencies );
          console.log('baseCurrencies');     
          console.log(this.baseCurrencies);     
        });
      
      break;
      
      case "2":
        console.log('cryptopia');
      break;
      
      default:
      console.log('unfilter');
      break;
    }
  }
  
   
  
  //This method will get called on Create button event
  createEntry(){
    console.log('create the entry');
    this.positionService.createPosition(this.newPosition)
    .subscribe((res) => {
      this.positionsList.push(res.data);
      this.newPosition = new Position();
    });
  }


}
