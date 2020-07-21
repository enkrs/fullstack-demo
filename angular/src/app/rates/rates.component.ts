import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { Rate } from '../rate';
import { RateService } from '../rate.service';

@Component({
  selector: 'app-rates',
  templateUrl: './rates.component.html'
})

export class RatesComponent implements OnInit {
  rates: Rate[]; // Satur šodienas valūtu kursus visām valūtām vienā lapā
  amount = 1;
  activePage = 1;
  pageNumbers = [];

  constructor(
    private rateService: RateService,
    private route: ActivatedRoute,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.loadRates();
  }

  loadRates(): void {
    this.rateService.getRates(this.activePage)
      .subscribe(ratePage => {
        this.rates = ratePage.items;
        // Nedaudz magic - izveido mainīgo [1,2,3,4,5,...] ar tik cik ir lapas paginatorā
        this.pageNumbers = Array.from({ length: +ratePage.totalPages }, Number.call, i => i + 1)
        this.activePage = +ratePage.currentPage;
      });
  }

  gotoPage(page: number): void {
    if (page == this.activePage) return;

    this.activePage = page;
    this.loadRates();
  }
}
