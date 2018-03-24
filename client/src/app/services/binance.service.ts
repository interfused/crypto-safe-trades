import Position from '../models/position.model';
import { Observable } from 'rxjs/Rx';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import {Response} from '@angular/http';
import { Injectable } from '@angular/core';

//RxJS operator for mapping the observable
import 'rxjs/add/operator/map';

@Injectable()
export class BinanceService {
  
  //LOCAL MEAN DEVELOPMENT
  /*
  api_url = 'http://localhost:3000';
  exchangeInfoUrl = `${this.api_url}/api/positions`;
  */
  
  //PHP API
  api_url = 'https://api.binance.com';
  //exchangeInfoUrl = `${this.api_url}/api/v1/exchangeInfo`;
  exchangeInfoUrl = 'http://dev.interfusedcreative.com/crypto-safe-trades-api/v1/binance/get-info.php';
  Symbol = [];
  
  constructor(
    private http: HttpClient
  ) { }
  
  /*
  //CREATE position uses Position object
  createPosition(position: Position): Observable<any>{
     //returns the observable of http post request 
    return this.http.post(this.exchangeInfoUrl, position);
  }
  */
  
  //READ positions (takes no argument)
  getInfo(): Observable<Symbol[]>{

    return this.http.get(this.exchangeInfoUrl)
    .map(res  => {
      //Maps the response object sent from the server
        
      return res["data"].symbols as Symbol[];
    });
  }
  /*
  //UPDATE
  updatePosition(position: Position) {

    return this.http.put(this.exchangeInfoUrl, position);
  }
  
  //DELETE
  removePosition(id: string): any{
    //delete obj by id
    let deleteUrl = `${this.exchangeInfoUrl}/${id}`;
    return this.http.delete(deleteUrl)
    .map(res  => {
      return res;
    });
  }
  */
  
  //Default Error handling method.
  private handleError(error: any): Promise<any> {
    console.error('An error occurred', error); // for demo purposes only
    return Promise.reject(error.message || error);
  }
  
}