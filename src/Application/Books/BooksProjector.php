<?php

namespace Francken\Application\Books;

use Francken\Application\ReadModel\MemberList\MemberList;

use Broadway\ReadModel\Projector;
use Francken\Application\Books\AvailableBook;
use Francken\Domain\Books\Events\BookOffered;
use Francken\Domain\Books\Events\BookOfferRetracted;
use Francken\Domain\Books\Events\BookSoldToMember;
use Francken\Domain\Books\Events\BookSaleCancelled;
use Francken\Domain\Books\Events\BookSaleCompleted;

final class BooksProjector extends Projector
{
    private $books;
    private $bookDetailRepository;

    public function __construct(Repository $books, BookDetailsRepositoryI $repo)
    {
        $this->bookDetailRepository = $repo;
        $this->books = $books;
    }

    public function whenBookOffered(BookOffered $event)
    {
        $bookDetails = $this->bookDetailRepository->getByISBN($event->isbn());

        $book = new AvailableBook(
            $event->bookId(),
            $bookDetails->title(),
            $bookDetails->author(),
            $event->price(),
            $event->isbn(),
            $bookDetails->pathToCover(),
            false); //salePending

        $this->books->save($book);
    }

    public function whenBookOfferRetracted(BookOfferRetracted $event)
    {
        $this->books->remove($event->bookId());
    }

    public function whenBookSoldToMember(BookSoldToMember $event)
    {
        $book = $this->books->find((string)$event->bookId());

        $book = new AvailableBook(
            $book->bookId(),
            $book->title(),
            $book->author(),
            $book->price(),
            $book->isbn(),
            $book->pathToCover(),
            true);

        $this->books->save($book);
    }

    public function whenBookSaleCancelled(BookSaleCancelled $event)
    {
        $book = $this->books->find((string)$event->bookId());

        $book = new AvailableBook(
            $book->bookId(),
            $book->title(),
            $book->author(),
            $book->price(),
            $book->isbn(),
            $book->pathToCover(),
            false);

        $this->books->save($book);
    }

    public function whenBookSaleCompleted(BookSaleCompleted $event)
    {
        $this->books->remove($event->bookId());
    }
}
