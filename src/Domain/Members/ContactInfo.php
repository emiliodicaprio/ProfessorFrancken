<?php

namespace Francken\Domain\Members;

use Broadway\Serializer\SerializableInterface;
use Francken\Domain\Base\Serializable;

final class ContactInfo implements SerializableInterface
{
    use Serializable;

    private $email;
    private $address;

    private function __construct(Email $email, Address $address)
    {
        $this->email = $email;
        $this->address = $address;
    }

    public static function describe(Email $email, Address $address)
    {
        $contact = new ContactInfo($email, $address);

        return $contact;
    }

    public function email() : Email
    {
        return $this->email;
    }

    public function address() : Address
    {
        return $this->address;
    }
}
