<?php

namespace SuluDeveloperBundle\DataFixtures\Document;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Sulu\Bundle\DocumentManagerBundle\DataFixtures\DocumentFixtureInterface;
use Sulu\Component\DocumentManager\DocumentManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sulu\Bundle\MediaBundle\Entity\Media;
use Sulu\Component\Content\Document\WorkflowStage;

class LoadSnippetFixture implements DocumentFixtureInterface
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
        $monkeys = array(
            'Abang ' => '(born 1966)—orangutan, taught to use and make a stone tool (cutting flake)',
            'Ai' => '(born 1976)—chimpanzee, studied by scientists at Primate Research Institute, Kyoto University',
            'Bonnie—orangutan' => 'began whistling (mimicking an animal caretaker), which is changing ideas about primate sound repertoires',
            'Chantek' => '(born 1977)—orangutan, involved with language research and ApeNet language-using great ape ambassador',
            'Clint—chimpanzee' => 'source of DNA for Chimpanzee Genome Project, Yerkes Primate Center[19]',
            'Cooper—chimpanzee' => 'Studied by Renato Bender and Nicole Bender for swimming and diving behavior in apes [20]',
            'Enos' => '(died 1962)—chimpanzee, spacefaring, after Ham',
            'Flo' => '(died 1972)—chimpanzee, key member of the Kasakela Chimpanzee Community studied by Jane Goodall; received an obituary in the Sunday Times',
            'Frodo' => '(1976-2013)—chimpanzee, baby-eating "bully", attacked Jane Goodall and Gary Larson',
            'Gua—chimpanzee' => 'raised as a child by the Drs. Kellogg alongside their son Donald',
            'Ham the Chimp' => '(1956–1983)—chimpanzee; spacefaring, before Enos',
            'Jenny—orangutan' => 'encountered and described by Charles Darwin in March 1838 at London Zoo.[21]',
            'Kanzi' => '(born 1980)—bonobo, involved with language research and tool invention, ApeNet language-using great ape ambassador',
            'Koko' => '(born 1971)—gorilla, involved with sign language research and ApeNet language-using great ape ambassador',
            'Lana—chimpanzee' => 'reared at Yerkes National Primate Research Center as part of its language analogue project',
            'Lucy—chimpanzee' => 'cross-fostered and raised by University of Oklahoma psychotherapist',
            'Nim Chimpsky' => '(1973–2000)—chimpanzee, named after linguist Noam Chomsky',
            'Nyota' => '(born 1998)—bonobo, Panbanisha\'s son',
            'Oliver' => 'the chimp—chimpanzee, the so-called "Missing Link", apparent "humanzee"',
            'Panbanisha—bonobo' => 'at the same research center as Kanzi',
            'Sarah' => '(chimpanzee)—research primate whose cognitive skills are documented in The Mind of an Ape',
            'Sultan—chimpanzee' => 'used in classic Kohler tool-use studies',
            'Suryia—orangutan' => 'studied by Renato Bender and Nicole Bender for swimming and diving behavior in apes',
            'Titus' => '(1974-2009)—gorilla, an extensively observed silverback mountain gorilla',
            'Washoe' => '(1965–2007)—chimpanzee, pioneer ape of hand-signing research',
        );

        foreach ($monkeys as $name => $description) {
            $monkey = $documentManager->create('snippet');
            $monkey->setStructureType('monkey');
            $monkey->setTitle($name);
            $monkey->setWorkflowStage(WorkflowStage::PUBLISHED);
            $monkey->getStructure()->bind(array(
                'description' => $description
            ));
            $documentManager->persist($monkey, 'de');
        }

        $documentManager->flush();
    }
}
