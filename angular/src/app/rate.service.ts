import { Observable } from 'rxjs';
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Rate } from './rate';
import { RatePage } from './rate.page';

@Injectable({
  providedIn: 'root'
})
export class RateService {

  constructor(private http: HttpClient) { }

  // api/rate dod objektu ar noteiktu daudzumu valūtu kursiem un paginatora datiem
  getRates(page: number): Observable<RatePage> {
    return this.http.get<RatePage>(
      '/api/rate',
      { params : { page: `${page}` }}
    );
  }

  // api/rate/USD dod masīvu ar izvēlētās valūtas kursu vēsturi sasortētu dilstošā secībā
  getRate(currency: string): Observable<Rate[]> {
    return this.http.get<Rate[]>(`/api/rate/${currency}`);
  }
}

