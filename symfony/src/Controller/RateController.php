<?php
/**
 * @author Kristaps Enkuzens <kristaps@kecom.lv>
 *
 * API REST kontrolieri taisīju ar rokām parastu Symfony kontrolieri.
 * Lai kautko demo sanāktu arī pašām programmēt. Cik saprotu, ikdienā
 * visi vienkārši lieto "friendsofsymfony/rest-bundle"
 * vai "willdurand/hateoas-bundle".
 */

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CurrencyRateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api", name="api_")
 */
class RateController extends AbstractController
{
    const PER_PAGE = 10;

    /**
     * @Route("/rate", name="rate_index")
     */
    public function indexAction(Request $request, CurrencyRateRepository $repo)
    {
        // Šī $date fīča tālāk frontendā netiek izmantota,
        // tomēr doma ir ka GET /rates atgriež jaunākā pieejamā datuma kursus,
        // un GET /rates?date=2020-07-18 atgriež norādītā datuma kursus.

        $date = (string)$request->query->get('date', null);
        if (!$date) {
            $date = $repo->getLatestDate();
        }

        $page = (int)$request->query->get('page', 1);
        $query = $repo->getByDateQuery(
            $date,
            self::PER_PAGE,
            ($page - 1) * self::PER_PAGE
        );
        $paginator = new Paginator($query);

        return $this->json([
            'currentPage' => $page,
            'totalPages' => ceil(count($paginator) / self::PER_PAGE),
            'totalItems' => count($paginator),
            'items' => $query->getResult(),
        ]);
    }

    /**
     * @Route("/rate/{currency}", name="rate_get", requirements={"currency"="[A-Z]+"})
     */
    public function getAction(string $currency, CurrencyRateRepository $repo)
    {
        // Atgriež vienu konkrētu valūtas kursu ar visiem vēstures ierakstiem.

        return $this->json($repo->findBy(
            ['currency'=>$currency],
            ['date'=>'DESC']
        ));
    }
}
