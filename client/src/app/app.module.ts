import { RouterModule, Routes } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { PositionService } from './services/position.service';

import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';


import { AppComponent } from './app.component';
import { HelpComponent } from './help/help.component';
import { UserProfileComponent } from './user-profile/user-profile.component';
import { TradesComponent } from './trades/trades.component';
import { PageNotFoundComponent } from './page-not-found/page-not-found.component';
import { PasswordComponent } from './user-profile/password/password.component';

//ROUTING
const appRoutes: Routes = [
  {path: 'tradesBackup', redirectTo: '/trades', pathMatch: 'full' },
  {path: '', component: TradesComponent },
  {path: 'help', component: HelpComponent},
  {path: 'my-account', component: UserProfileComponent},
  {path: '**', component: PageNotFoundComponent}
]

@NgModule({
  declarations: [
    AppComponent,
    HelpComponent,
    UserProfileComponent,
    TradesComponent,
    PageNotFoundComponent,
    PasswordComponent
  ],
  imports: [
    RouterModule.forRoot(
      appRoutes,
      { enableTracing: true } // <-- debugging purposes only
    ),
    BrowserModule,
    HttpClientModule,
    FormsModule
  ],
  providers: [
    PositionService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
