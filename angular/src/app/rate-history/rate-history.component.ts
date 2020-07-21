import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Rate } from '../rate';
import { RateService } from '../rate.service';

// Šis komponents parāda vienas valūtas vēsturiskos datus

@Component({
  selector: 'app-rate-history',
  templateUrl: './rate-history.component.html',
})
export class RateHistoryComponent implements OnInit {
  rate: Rate;
  oldRates: Rate[]; // Satur vienu izvēlētu valūtu, vairākus datumus dilstošā secībā

  constructor(
    private rateService: RateService,
    private route: ActivatedRoute,
  ) { }

  ngOnInit(): void {
    this.route.paramMap.subscribe(params => {
      if (!params.has('currency')) {
        this.oldRates = [];
        return;
      }

      this.rateService.getRate(params.get('currency'))
        .subscribe(old => {
          this.rate = old.shift()
          this.oldRates = old
        });
      });
  }
}
