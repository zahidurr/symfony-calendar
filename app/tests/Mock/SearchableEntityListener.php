<?php

namespace App\Tests\Mock;

use Doctrine\ORM\Event\LifecycleEventArgs;
use StirlingBlue\SearchBundle\EventListeners\SearchableEntityListener as BaseListener;
use StirlingBlue\SearchBundle\Factory\SearchMessageFactory;
use StirlingBlue\SearchBundle\SearchDocumentInterface;
use StirlingBlue\SearchBundle\Service\AwsSearchMessageSender;

class SearchableEntityListener extends BaseListener
{
    use MockTrait;

    public function __construct(SearchMessageFactory $searchMessageFactory, AwsSearchMessageSender $awsSearchMessageSender)
    {
        parent::__construct($searchMessageFactory, $awsSearchMessageSender);
        $this->doMock();
    }

    public function postPersist(SearchDocumentInterface $searchDocument, LifecycleEventArgs $args)
    {
        $this->mock || parent::postPersist($searchDocument, $args);
    }

    public function postRemove(SearchDocumentInterface $searchDocument, LifecycleEventArgs $args)
    {
        $this->mock || parent::postRemove($searchDocument, $args);
    }

    public function postUpdate(SearchDocumentInterface $searchDocument, LifecycleEventArgs $args)
    {
        $this->mock || parent::postUpdate($searchDocument, $args);
    }
}
