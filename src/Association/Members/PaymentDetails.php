<?php

declare(strict_types=1);

namespace Francken\Association\Members;

use Francken\Association\LegacyMember;

final class PaymentDetails
{
    private ?string $iban = null;

    private ?string $bic = null;

    private bool $deductAdditionalCosts = false;

    /**
     * In the future we will also store the bic code, but as that would require
     * running a migration on our old database we will omit it for now
     */
    public function __construct(
        ?string $iban,
        ?string $bic = null,
        bool $deductAdditionalCosts = false
    ) {
        $this->iban = $iban;
        $this->bic = $bic;
        $this->deductAdditionalCosts = $deductAdditionalCosts;
    }

    public function iban() : ?string
    {
        return $this->iban;
    }

    public function bic() : ?string
    {
        return $this->bic;
    }

    /**
     * For privacy reasons we mask the member's iban account number
     */
    public function maskedIban() : ?string
    {
        if ($this->iban === null) {
            return null;
        }

        // Take only alpha numerical characters from the iban
        $iban = preg_replace('/[^\d\w]+/', '', $this->iban);

        // Separate the iban into pieces of 4 characters
        $pieces = (int)floor(strlen($iban) / 4);

        // Mask the first 3 pieces
        $maskedPieces = array_map(
            fn (int $idx) => ($idx < 3) ? 'XXXX' : substr($iban, $idx * 4, 4),
            range(0, $pieces)
        );

        return implode('-', $maskedPieces);
    }

    public function paymentMethod() : string
    {
        return $this->deductAdditionalCosts ? 'Afschrijven' : 'Contant';
    }

    public function freeMembership() : bool
    {
        return false;
    }

    public function deductAdditionalCosts() : bool
    {
        return $this->deductAdditionalCosts;
    }

    public static function fromDb(LegacyMember $member) : self
    {
        $deductAdditionalCosts = $member->streeplijst === 'Afschrijven';

        return new self($member->rekeningnummer, null, $deductAdditionalCosts);
    }
}
