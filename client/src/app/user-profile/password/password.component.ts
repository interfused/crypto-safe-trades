import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-password',
  templateUrl: './password.component.html',
  styleUrls: ['./password.component.scss']
})
export class PasswordComponent implements OnInit {
  
  confirmDisabled = 'disabled';
  allowPasswordChange = false;
  disabledSaveButton = "disabled";
  
  newPassword = '';
  
  //password validation
  validPasswordLength = false;
  validPasswordUseNum = false;
  validPasswordUseUpper = false;
  validPasswordUseLower = false;
  validPasswordUseSpecialChar = false;
  validPasswordMatch = false;
  reqPasswordValidationPoints = 4; //required number of valdation points for password
  minPasswordLength = 8;
  maxPasswordLength = 15;
  
  
  constructor() { }

  ngOnInit() {
  }
  
  checkOldPassword(val){
    this.allowPasswordChange = false;
    if(val === 'asdfasdf'){
      this.allowPasswordChange = true;
    }
    console.log('entered old password of: ' + val + ' allow pw change: '+this.allowPasswordChange);
  }
  
  validateNewPassword(str){
    let pw = str.trim();
    this.newPassword = pw;
    
    let validationPoints = 0;
    
    this.validPasswordLength = false;
    this.validPasswordUseNum = false;
    this.validPasswordUseUpper = false;
    this.validPasswordUseLower = false;
    this.validPasswordUseSpecialChar = false;
    this.validPasswordMatch = false;
    
    this.disabledSaveButton = 'disabled';
    
    if((pw.length >= this.minPasswordLength) && (pw.length <= this.maxPasswordLength) ){
      this.validPasswordLength = true;
      validationPoints++;
    }
    
    //https://stackoverflow.com/questions/14850553/javascript-regex-for-password-containing-at-least-8-characters-1-number-1-uppe
    //one digit
    let regex = new RegExp("^(?=.*\[0-9])");
    if(regex.test(pw)){
      this.validPasswordUseNum = true;
      validationPoints++;
    }
    
    //lower case
    regex = new RegExp("^(?=.*\[a-z])");
    if(regex.test(pw)){
      this.validPasswordUseLower = true;
      validationPoints++;
    }
    
    //upper case
    regex = new RegExp("^(?=.*\[A-Z])");
    if(regex.test(pw)){
      this.validPasswordUseUpper = true;
      validationPoints++;
    }
    
    //special case
    //regex = new RegExp("^(?=.*\[!@#\$%\^\&*\)\(+=._-])");
    regex = new RegExp( "/[-!$%^&*()_+|~=`{}[]:/;<>?,.@#]/" );
    if(regex.test(pw)){
      this.validPasswordUseUpper = true;
      validationPoints++;
    }
    
    
    
    this.confirmDisabled = (validationPoints === this.reqPasswordValidationPoints) ? '' : 'disabled';
    
  }
  
  matchPassword(str){
    console.log('matchPassword: '+str+ ' test: '+str === this.newPassword);
    this.disabledSaveButton = (str === this.newPassword) ? '' : 'disabled'; 
  }
  
  updatePassword(){
    alert('password will be updated to: '+ this.newPassword);
  }

}
