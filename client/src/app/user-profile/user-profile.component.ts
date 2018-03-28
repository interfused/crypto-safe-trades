import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-user-profile',
  templateUrl: './user-profile.component.html',
  styleUrls: ['./user-profile.component.scss']
})
export class UserProfileComponent implements OnInit {
  
//  exchanges = ['Binance','Kucoin','Cryptopia'];
  
  exchanges = [{
"name": "Binance",
"key": '',
'secret': ''
}, {
"name": "Kucoin",
'key': '',
'secret': ''
 }, {
"name": "bump_3",
'key': '',
'secret': ''
}]
  
  apiKeyBinance = 'key1';
  apiSecretBinance = 'secret1';
  
  /*
  for(let i=0; i<exchanges.length; i++){
    //apiKey-Binance
    eval('apiKey-' + exchanges[i] ) = '';
  }
  */
  
  test = 'This is a test';
    
  constructor() { 
    
  }

  ngOnInit() {
    
  }
  
  updateApiDetails(key, secret){
    alert('key is: '+key+' and secret is: '+secret);
  }
  
  getExchangeKey(exchange){
    return eval('apiKey' + exchange);
  }

}
