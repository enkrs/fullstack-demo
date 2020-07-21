Hei!
====
Šis ir mans testa uzdevuma risinājums. Uzdevums:

> Jāuztaisa lietojums, kas nolasa valūtu kursus no https://www.bank.lv/vk/ecb_rss.xml RSS barotnes un attēlo to lietotājam.
> Uzdevumam izmantot MySQL(MariaDB), Symfony vai Laravel ietvaru un Angular ietvaru.

Tehnoloģija
-----------
Backendam izvēlējos Symfony un frontendam Angular, jo tās ir vakances standarta "stacks".
 - **/symfony** - Symfony backend aplikācija
 - **/angular** - Angular frontend aplikācija
 - **/mariadb** - Faili priekš mariadb konteinera

Applikācijas palaišana
----------------------
Demo var palaist ar komandu:

    docker-compose up --build

Tā izveidos nepieciešamos konteinerus un palaidīs aplikāciju. Kad aplikācija palaista un dockerī izveidojusies db, pirmoreiz nepieciešams palaist datubāzu migrēšanu. Jaunā terminālī:

    docker-compose run --rm symfony php bin/console doctrine:migrations:migrate --no-interaction

Pēc tam, terminālī lai uzreiz iegūtu datus no bank.lv jāpalaiž:

    docker-compose run --rm symfony php bin/console app:get-rates

(Šī ir komanda, ko vajadzētu vai nu likt hosta cronā ar `20 03 * * * *`, vai arī būtu jāpārstrādā nedaudz Docker configs lai symfony konteinerā palaiž gan crond gan php-fpm)

Tālāk aplikāciju var atvērt savā pārlūkprogrammā [http://localhost:8080/](http://localhost:8080/)

Docker Compose Arhitektūra
--------------------------
Programma sastāv no 3 konteineriem:
- **symfony** - php-fpm konteiners, kas uzinstalē Symfony aplikāciju ar composer un servē to pa tcp;
- **mariadb** - datubāzes konteiners, dati tiek turēti docker volume;
- **angular** - nginx konteiners: uzbūvē Angular aplikāciju ar npm. Statiskie angular faili tiek servēti pa http no šejienes un pārējie /api requesti tiek padoti uz "symfony" konteineru.


Symfony
-------
Priekš demo, no 0 taisītos failos pieliku klāt strict_types=1 un typehintus, kur iespējams - vairāk izklaides pēc. Failus formatēju pēc Symfony format. Lietoju tīru Symfony installu bez community bundliem, kas nedaudz sarežģītu kodu priekš jebkā vairāk par mazu demo.

Reālie faili, kur ir kods, secībā no interesantiem uz mazāk interesantiem:
- [Service/BankXml.php](symfony/src/Service/BankXml.php) - algoritms XML ielādei un apstrādei;
- [Entity/CurrencyRate.php](symfony/src/Entity/CurrencyRate.php) - komentāri par db struktūru;
- [Service/CurrencyManager.php](symfony/src/Service/CurrencyManager.php) - saglabā valūtas datubāzē;
- [Controller/RateController.php](symfony/src/Controller/RateController.php) API kontrolieris - darbs ar datubāzes pieprasījumiem, pagination;
- [Command/GetRatesCommand.php](symfony/src/Command/GetRatesCommand.php) Komandlīnijas programma, kas iegūst datus no bank.lv.

Angular
-------
Mazs angular projektiņš ar 2 komponentiem, routeri. Priekš demo kompaktuma, viss SASS stils
vienā failā, nevis atsevišķi.

Faili ar kodu ir:
 - [app/rates/rates.component.ts](angular/src/app/rates/rates.component.ts) - Komponents kas atēlo valūtu kursus;
 - [app/rate.service.ts](angular/src/app/rate.service.ts) - Serviss datu ielādēšanai pa HTTP;
 - [styles.sass](angular/src/styles.sass) - priekš demo kompaktuma - minimāli stili kopā šeit, nevis iekšā komponentos.
 - [app/rate-history/rate-history.component.ts](angular/src/app/rate-history/rate-history.component.ts) - Komponents kas parāda vienas valūtas vēsturi
