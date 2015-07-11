<?php

namespace SuluDeveloperBundle\DataFixtures\Document;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Sulu\Bundle\DocumentManagerBundle\DataFixtures\DocumentFixtureInterface;
use Sulu\Component\DocumentManager\DocumentManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sulu\Bundle\MediaBundle\Entity\Media;
use Sulu\Component\Content\Document\WorkflowStage;

class LoadPageFixture implements DocumentFixtureInterface
{
    private $container;

    public function getOrder()
    {
        return 10;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(DocumentManager $documentManager)
    {
        $this->loadCities($documentManager);
        $this->loadViennaDistricts($documentManager);
    }

    private function loadCities(DocumentManager $documentManager)
    {
        $page = $documentManager->create('page');
        $page->setStructureType('city');
        $page->setTitle('Carthage');
        $page->setResourceSegment('/carthage');
        $page->getStructure()->bind(array(
            'article' => <<<EOT
The city of Carthage (/ˈkɑrθɪdʒ/; Arabic: قرطاج‎ Qarṭāj) is a city in Tunisia that was once the centre of the ancient Carthaginian civilization. The city developed from a Phoenician colony of the 1st millennium BC into the capital of an ancient empire.[2] The area of Carthage was before inhabited by Berber people who also became the bulk of Carthage's population and constituted a significant part of its army, economy and administration. Native Berbers and settling Phoenicians in Carthage mixed in different ways including religion and language, creating the Punic language and culture.
EOT
        ,
        ));
        $documentManager->persist($page, 'en', array(
            'parent_path' => '/cmf/sulu_io/contents',
        ));

        $page = $documentManager->create('page');
        $page->setStructureType('city');
        $page->setTitle('Alexandria');
        $page->setResourceSegment('/alexandria');
        $page->setWorkflowStage(WorkflowStage::PUBLISHED);
        $page->getStructure()->bind(array(
            'article' => <<<EOT
Alexandria (/ˌælɪɡˈzændrɪə/ or /ˌælɪɡˈzɑːndrɪə/;[1] اسكندرية, pronounced [eskendeˈrejjæ] in Egyptian Arabic)[see other names] is the second largest city and a major economic centre in Egypt, extending about 32 km (20 mi) along the coast of the Mediterranean Sea in the north central part of the country. It is also the largest city lying directly on the Mediterranean coast. Alexandria is Egypt's largest seaport, serving approximately 80% of Egypt's imports and exports. It is an important industrial center because of its natural gas and oil pipelines from Suez. Alexandria is also an important tourist resort.
EOT
        ,
        ));
        $documentManager->persist($page, 'en', array(
            'parent_path' => '/cmf/sulu_io/contents',
        ));

        $page = $documentManager->create('page');
        $page->setStructureType('city');
        $page->setTitle('Vienna');
        $page->setResourceSegment('/vienna');
        $page->setWorkflowStage(WorkflowStage::PUBLISHED);
        $page->getStructure()->bind(array(
            'article' => <<<EOT
Vienna (/viˈɛnə/;[5][6] German: Wien, pronounced [viːn] ( listen)) is the capital and largest city of Austria, and one of the nine states of Austria. Vienna is Austria's primary city, with a population of about 1.794 million[7] (2.6 million within the metropolitan area,[4] nearly one third of Austria's population), and its cultural, economic, and political centre. It is the 7th-largest city by population within city limits in the European Union. Until the beginning of the 20th century it was the largest German-speaking city in the world, and before the splitting of the Austro-Hungarian Empire in World War I the city had 2 million inhabitants.[8] Today it is the second only to Berlin in German speakers.[9][10] Vienna is host to many major international organizations, including the United Nations and OPEC. The city lies in the east of Austria and is close to the borders of the Czech Republic, Slovakia, and Hungary. These regions work together in a European Centrope border region. Along with nearby Bratislava, Vienna forms a metropolitan region with 3 million inhabitants. In 2001, the city centre was designated a UNESCO World Heritage Site.[11]
EOT
        ,
        ));
        $documentManager->persist($page, 'en', array(
            'parent_path' => '/cmf/sulu_io/contents',
        ));

        $page = $documentManager->create('page');
        $page->setStructureType('city');
        $page->setTitle('Jerusalem');
        $page->setResourceSegment('/jurusalem');
        $page->getStructure()->bind(array(
            'article' => <<<EOT
Jerusalem (/dʒəˈruːsələm/; Hebrew: יְרוּשָׁלַיִם  Yerushaláyim; Arabic: القُدس‎  al-Quds),[i] located on a plateau in the Judean Mountains between the Mediterranean and the Dead Sea, is one of the oldest cities in the world. It is considered holy to the three major Abrahamic religions—Judaism, Christianity and Islam. Israelis and Palestinians both claim Jerusalem as their capital, as Israel maintains its primary governmental institutions there and the State of Palestine ultimately foresees it as its seat of power; however, neither claim is widely recognized internationally.
EOT
        ,
        ));
        $documentManager->persist($page, 'en', array(
            'parent_path' => '/cmf/sulu_io/contents',
        ));

        $documentManager->flush();
    }

    private function loadViennaDistricts(DocumentManager $documentManager)
    {
        $districts = array(
            'Innere Stadt' => 'is the city centre, with numerous historical sites and few residents.',
            'Leopoldstadt' => 'is the island between the Danube and the Danube Canal, with Praterstern, Vienna\'s most frequented traffic spot, and Giant Wheel.',
            'Landstrasse' => 'is on the right bank of the Danube Canal, and includes the Belvedere.',
            'Wieden' => 'is a small district south of the city centre.',
            'Margareten' => ' was separated from Wieden in 1861.',
            'Mariahilf' =>  'is a small district on the main shopping lane leading to Westbahnhof.',
            'Neubau' =>  'is a small district on main shopping lane leading to Westbahnhof, and includes the Museums Quarter, a large cultural complex.',
            'Josefstadt' =>  'is a small district close to City Hall, Parliament, and Vienna University.',
            'Alsergrund' =>  'is the General Hospital district, and includes Sigmund Freud\'s residence.',
            'Favoriten' =>  'is in the southern part of Vienna, and has the largest population, the new main train station, and the city\'s thermal spa.',
            'Simmering' =>  'is on the right bank of the Danube Canal, and includes the Central Cemetery.',
            'Meidling' =>  'is on the southern bank of the Wien river.',
            'Hietzing' =>  'is on the southern bank of the Wien river, and includes Schönbrunn Palace.',
            'Penzing' =>  'is on the northern bank of the Wien river, and was separated from Hietzing in 1938. It includes Otto Wagner\'s Church Am Steinhof.',
            'Rudolfshei-Funfhaus' => 'is on the northern bank of the Wien river. Until 1938, it consisted of the 14th and 15th district and was called Westbahnhof.',
            'Ottakring' =>  'is on the western outskirts and includes Vienna\'s traditional brewery.',
            'Hernals' =>  'is on the northwestern outskirts of Vienna.',
            'Wahring' =>  'is on the northwestern outskirts of Vienna, and includes the Central Institute of Meteorology.',
            'Dobling' =>  'is on the northern outskirts of Vienna, and includes the classical Heurigen district.',
            'Brigittenau' =>  'is on the same island as Leopoldstadt, and was separated in 1900.',
            'Floridsdorf' =>  'is on the left bank of the Danube, and includes industry areas. It is the northernmost part of Vienna.',
            'Donaustadt' =>  'is on the left bank of the Danube, and is the largest district in size. It includes UN City, the largest convention hall in Vienna.',
            'Liesing' =>  'is the southernmost district, and includes industrial areas.',
        );

        foreach ($districts as $name => $district) {
            $page = $documentManager->create('page');
            $page->setStructureType('district');
            $page->setTitle($name);
            $page->setWorkflowStage(WorkflowStage::PUBLISHED);
            $page->getStructure()->bind(array(
                'description' => $district,
            ));
            $page->setResourceSegment('/vienna/' . str_replace(' ', '', $name));

            $documentManager->persist($page, 'en', array(
                'parent_path' => '/cmf/sulu_io/contents/vienna',
            ));
        }

        $documentManager->flush();
    }
}
