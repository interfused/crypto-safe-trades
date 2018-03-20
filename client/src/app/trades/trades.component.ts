import { PositionService } from '../services/position.service';
import  Position  from '../models/position.model';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-trades',
  templateUrl: './trades.component.html',
  styleUrls: ['./trades.component.scss']
})
export class TradesComponent implements OnInit {

  constructor(
    //Private todoservice will be injected into the component by Angular Dependency Injector
    private positionService: PositionService)
  {
    console.log('constructed');  
  }
  
  //Declaring the new position Object and initilizing it
  public newPosition: Position = new Position();
  
  //An Empty list for the visible positions list
  positionsList: Position[];
  editPositions: Position[] = [];

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
