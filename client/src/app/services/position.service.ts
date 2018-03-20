import Position from '../models/position.model';
import { Observable } from 'rxjs/Rx';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import {Response} from '@angular/http';
import { Injectable } from '@angular/core';

//RxJS operator for mapping the observable
import 'rxjs/add/operator/map';

@Injectable()
export class PositionService {
  
  //LOCAL MEAN DEVELOPMENT
  /*
  api_url = 'http://localhost:3000';
  positionUrl = `${this.api_url}/api/positions`;
  */
  
  //PHP API
  api_url = 'http://dev.interfusedcreative.com/crypto-safe-trades-api';
  positionUrl = `${this.api_url}/v1/trade-entries`;
  
  constructor(
    private http: HttpClient
  ) { }
  
  
  //CREATE position uses Position object
  createPosition(position: Position): Observable<any>{
     //returns the observable of http post request 
    return this.http.post(this.positionUrl, position);
  }
  
  //READ positions (takes no argument)
  getPositions(): Observable<Position[]>{

    return this.http.get(this.positionUrl)
    .map(res  => {
      //Maps the response object sent from the server
        
      return res["data"].records as Position[];
    });
  }
  
  //UPDATE
  updatePosition(position: Position) {

    return this.http.put(this.positionUrl, position);
  }
  
  //DELETE
  removePosition(id: string): any{
    //delete obj by id
    let deleteUrl = `${this.positionUrl}/${id}`;
    return this.http.delete(deleteUrl)
    .map(res  => {
      return res;
    });
  }
  
  //Default Error handling method.
  private handleError(error: any): Promise<any> {
    console.error('An error occurred', error); // for demo purposes only
    return Promise.reject(error.message || error);
  }
  
}