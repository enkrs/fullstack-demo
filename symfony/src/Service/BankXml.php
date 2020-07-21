<?php
/**
 * @author Kristaps Enkuzens <kristaps@kecom.lv>
 * 
 * Ielādē no bank.lv datus, pārveido tos nedaudz ērtākā array
 * lai tālāk varētu ievietot datubāzē.
 *
 * Varbūt reālā appā es nodalītu atsevišķās klasēs xml ielādi no
 * Interneta un pašu šo array dekodēšānu. Tāpat uzmetu aci Symfony/Serializer
 * bet šajā gadījumā manuprāt nekas elegants arī ar to neizdotos..
 */

declare(strict_types=1);

namespace App\Service;

/**
 * Pieeja pie valūtas kursiem XML no bank.lv.
 */
class BankXml
{
    public const XML_URL = 'https://www.bank.lv/vk/ecb_rss.xml';

    /**
     * Atgriež valūtu kursus sekojošā masīvā:
     * [
     *   'Thu, 16 Jul 2020 03:00:00 +0300' => [
     *     'AUD' => '1.63380000',
     *     'BGN' => '1.95580000',
     *     'BRL' => '6.11540000',
     *     ....
     *   ]
     *   'Wed, 15 Jul 2020 03:00:00 +0300' => [
     *     ...
     *   ]
     * ].
     */
    public function getArray(): array
    {
        $dates = [];
        $xml = simplexml_load_file(self::XML_URL);
        // Mūsu interesējošā XML daļa:
        // <channel>
        //   ..
        //   <item>
        //     <pubDate>Thu, 16 Jul 2020 03:00:00 +0300</pubDate>
        //     <description>AUD 1.63380000 BGN 1.95580000 ...</description>
        //   </item>
        //   <item>
        //     ..

        foreach ($xml->channel->item as $xmlNode) {
            $dates[(string) $xmlNode->pubDate] = $this->stringPairsToArray(
                trim((string) $xmlNode->description)
            );
        }

        return $dates;
    }

    /**
     * Funkcija dekodē "AA BB CC DD" stringu uz ['aa'=>'bb', 'cc'=>'dd'].
     */
    protected function stringPairsToArray(string $currencyString): array
    {
        $return = [];

        $key = null;
        foreach (explode(' ', $currencyString) as $token) {
            if (null === $key) {
                $key = $token;
            } else {
                $return[$key] = $token;
                $key = null;
            }
        }

        return $return;
    }
}
