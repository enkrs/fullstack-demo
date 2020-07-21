import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { RatesComponent } from './rates/rates.component';
import { RateHistoryComponent } from './rate-history/rate-history.component';

const routes: Routes = [
  { path: '', redirectTo: '/rate', pathMatch: 'full' },
  {
    path: 'rate',
    component: RatesComponent,
    children: [
      { path: ':currency', component: RateHistoryComponent }
    ]
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
