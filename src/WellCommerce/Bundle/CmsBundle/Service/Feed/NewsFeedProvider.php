<?php

namespace WellCommerce\Bundle\CmsBundle\Service\Feed;

use Debril\RssAtomBundle\Provider\FeedContentProviderInterface;
use FeedIo\Feed;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\AbstractContainerAware;

/**
 * Class NewsFeedProvider
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class NewsFeedProvider extends AbstractContainerAware implements FeedContentProviderInterface
{
    public function getFeedContent(array $options)
    {
        $feed = new Feed();
        $feed->setTitle($this->getShopStorage()->getCurrentShop()->getName());
        $feed->setLink($this->getRouterHelper()->generateUrl('front.news.' . $options['format']));
        $feed->setLastModified(new \DateTime());
        
        $this->addItems($feed);
        
        return $feed;
    }
    
    private function addItems(Feed $feed): Feed
    {
        $news = $this->get('news.dataset.front')->getResult('array', [
            'order_by'  => 'startDate',
            'order_dir' => 'desc',
        ]);
        
        foreach ($news['rows'] as $row) {
            $item = new Feed\Item();
            $item->setPublicId($row['id']);
            $item->setLink($this->getRouterHelper()->generateUrl('dynamic_' . $row['routeId'], [], UrlGeneratorInterface::ABSOLUTE_URL));
            $item->setTitle($row['topic']);
            $item->setDescription($row['summary']);
            $item->setLastModified($row['updatedAt']);
            $feed->add($item);
        }
        
        return $feed;
    }
}
