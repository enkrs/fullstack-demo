<?php
/**
 * @author Kristaps Enkuzens <kristaps@kecom.lv>
 *
 * Šis fails mēģina valūtu datus saglabāt datubāzē.
 * Ja importējamos datos ir diena, kuras dati jau ir datubāzē, tad tā diena
 * tiek izlaista.
 */

declare(strict_types=1);

namespace App\Service;

use App\Entity\CurrencyRate;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CurrencyManager
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return int cik jauni valūtu kursu ieraksti tika izveidoti
     */
    public function insertFromArray(array $currencyArray) : int
    {
        $i = 0;
        // $currencyArray = [
        //   'Thu, 16 Jul 2020 03:00:00 +0300' => ['AUD' => '1.63380000', ...],
        //   ..
        // ]
        foreach ($currencyArray as $timestamp => $currencies) {
            $date = new DateTime($timestamp);

            if (
                $this->entityManager->getRepository(CurrencyRate::class)
                ->count(['date' => $date]) > 0
            ) {
                // izlaist importu ja diena jau ir datubāzē
                continue;
            }

            // $currencies = [
            //    'AUD' => '1.63380000',
            //    ...
            // ]
            foreach ($currencies as $currency => $value) {
                $entity = new CurrencyRate();
                $entity->setDate($date);
                $entity->setCurrency($currency);
                $entity->setValue($value);
                $this->entityManager->persist($entity);
                $i++;
            }
            $this->entityManager->flush(); // katru dienu insertojam kopā
        }
        return $i;
    }
}
