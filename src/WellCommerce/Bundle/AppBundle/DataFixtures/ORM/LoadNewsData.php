<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\AppBundle\DataFixtures\ORM;

use Carbon\Carbon;
use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\CmsBundle\Entity\News;
use WellCommerce\Bundle\CmsBundle\Entity\NewsTranslation;
use WellCommerce\Bundle\CoreBundle\Helper\Helper;
use WellCommerce\Bundle\DoctrineBundle\DataFixtures\AbstractDataFixture;

/**
 * Class LoadNewsData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadNewsData extends AbstractDataFixture
{
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $this->createNews($manager);
        
        $this->createLayoutBoxes($manager, [
            'news' => [
                'type' => 'News',
                'name' => 'News',
            ],
        ]);
        
        $manager->flush();
    }
    
    protected function createNews(ObjectManager $manager)
    {
        for ($i = 0; $i <= 5; $i++) {
            $news = new News();
            $news->setStartDate(Carbon::now()->subMonth($i + 1));
            $news->setEndDate(Carbon::now()->subMonth($i + 2));
            $news->addShop($this->getReference('shop'));
            $sentence = $this->getFakerGenerator()->unique()->sentence(3);
            $topic    = substr($sentence, 0, strlen($sentence) - 1);
            foreach ($this->getLocales() as $locale) {
                /** @var NewsTranslation $translation */
                $translation = $news->translate($locale->getCode());
                $translation->setTopic($topic);
                $translation->setSlug(Helper::urlize($topic));
                $translation->setSummary($this->getFakerGenerator()->text(200));
                $translation->setContent($this->getFakerGenerator()->text(600));
            }
            
            $news->mergeNewTranslations();
            $manager->persist($news);
        }
    }
}
