<?php
/**
 * @author Kristaps Enkuzens <kristaps@kecom.lv>
 *
 * Tabulas struktūra: id, date, currency, value
 *
 * Valūtām lietoju decimal. Vienīgais interesantais ko šēit var parādīt:
 * uzliku unique constraint uz date+currency, jo dienas laikā nav iespējami
 * vairāki kursi vienai valūtai. Docrtine migrācija uzģenerējās pareiza un
 * nav labota manuāli.
 */

namespace App\Entity;

use App\Repository\CurrencyRateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CurrencyRateRepository::class)
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="currency_date_idx", columns={"date", "currency"})})
 */
class CurrencyRate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $currency;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=8)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
