<?php

declare(strict_types=1);

namespace Francken\Extern\Http;

use Francken\Extern\Contact;
use Francken\Extern\ContactDetails;
use Francken\Extern\Http\Requests\ContactDetailsRequest;
use Francken\Extern\Http\Requests\ContactRequest;
use Francken\Extern\LogoUploader;
use Francken\Extern\Partner;

final class AdminPartnerContactsController
{
    /**
     * @var LogoUploader
     */
    private $uploader;

    public function __construct(LogoUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function create(Partner $partner)
    {
        return view('admin.extern.partners.contacts.create', [
            'partner' => $partner,
            'contact' => new Contact(),
            'contactDetails' => new ContactDetails(),
            'breadcrumbs' => [
                ['url' => action([AdminPartnersController::class, 'index']), 'text' => 'Partners'],
                ['url' => action([AdminPartnersController::class, 'show'], ['partner' => $partner]), 'text' => $partner->name],
                ['url' => action([static::class, 'create'], ['partner' => $partner]), 'text' => 'Add contact'],
            ]
        ]);
    }

    public function store(ContactRequest $request, ContactDetailsRequest $contactDetailsRequest, Partner $partner)
    {
        $contact = new Contact([
            'firstname' => $request->firstname(),
            'surname' => $request->surname(),
            'initials' => $request->initials(),
            'position' => $request->position(),
            'title' => $request->title(),
            'gender' => $request->gender(),
            'notes' => $request->notes(),
        ]);
        $partner->contacts()->save($contact);

        $photo = $this->uploader->uploadContactPhoto(
            $request->photo,
            $partner,
            $contact
        );

        if ($photo !== null) {
            $contact->update(['photo_media_id' => $photo->id]);
            $contact->attachMedia($photo, Contact::CONTACT_PHOTO_TAG);
        }

        $contactDetails = $contactDetailsRequest->contactDetails();
        $contactDetails->partner_id = $partner->id;
        $contact->contactDetails()->save($contactDetails);

        return redirect()->action(
            [AdminPartnersController::class, 'show'],
            ['partner' => $partner]
        );
    }

    public function edit(Partner $partner, Contact $contact)
    {
        return view('admin.extern.partners.contacts.edit', [
            'partner' => $partner,
            'contact' => $contact,
            'contactDetails' => $contact->contactDetails,
            'breadcrumbs' => [
                ['url' => action([AdminPartnersController::class, 'index']), 'text' => 'Partners'],
                ['url' => action([AdminPartnersController::class, 'show'], ['partner' => $partner]), 'text' => $partner->name],
                ['url' => action([static::class, 'edit'], ['partner' => $partner, 'contact' => $contact]), 'text' => 'Edit contact'],
            ]
        ]);
    }

    public function update(ContactRequest $request, ContactDetailsRequest $contactDetailsRequest, Partner $partner, Contact $contact)
    {
        $contact->update([
            'firstname' => $request->firstname(),
            'surname' => $request->surname(),
            'initials' => $request->initials(),
            'position' => $request->position(),
            'title' => $request->title(),
            'gender' => $request->gender(),
            'notes' => $request->notes(),
        ]);

        $photo = $this->uploader->uploadContactPhoto(
            $request->photo,
            $partner,
            $contact
        );

        if ($photo !== null) {
            $partner->syncMedia($photo, Partner::PARTNER_LOGO_TAG);
            $contact->update(['photo_media_id' => $photo->id]);
        }
        $contact->contactDetails()->update(
            $contactDetailsRequest->contactDetails()->toArray()
        );

        return redirect()->action(
            [AdminPartnersController::class, 'show'],
            ['partner' => $partner]
        );
    }

    public function destroy(Partner $partner, Contact $contact)
    {
        $contact->delete();

        return redirect()->action(
            [AdminPartnersController::class, 'show'],
            ['partner' => $partner]
        );
    }
}
