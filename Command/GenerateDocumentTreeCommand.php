<?php

namespace Sulu\Bundle\DeveloperBundle\Command;

use PHPCR\Util\PathHelper;
use Sulu\Bundle\ContentBundle\Document\PageDocument;
use Symfony\Component\Console\Command\Command;
use Sulu\Component\DocumentManager\DocumentManager;
use Sulu\Bundle\DocumentManagerBundle\Bridge\DocumentInspector;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class GenerateDocumentTreeCommand extends ContainerAwareCommand
{
    private $manager;
    private $inspector;

    public function __construct(DocumentManager $manager, DocumentInspector $inspector)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->inspector = $inspector;
    }

    public function configure()
    {
        $this->setName('sulu:fixtures:generate:document-tree');
        $this->setDescription(
            'Generates a document tree via. a drunken walk.'
        );
        $this->addOption('depth', null, InputOption::VALUE_REQUIRED, 'Maximum depth', 4);
        $this->addOption('nb-documents', null, InputOption::VALUE_REQUIRED, 'Total number of documents to generate.', 100);
        $this->addOption('modprob', null, InputOption::VALUE_REQUIRED, 'Integer between 1 and 100 which determines how often the tree position will be modulated.', 25);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $treeActions = array('none', 'child', 'parent');
        $treeModProb = $input->getOption('modprob');
        $nbDocuments = $input->getOption('nb-documents');
        $maxDepth = $input->getOption('depth');
        $locales = array('en', 'de');

        $action = 'none';
        $basePath = '/cmf/sulu_io/contents/tree';
        $currentPath = $basePath;
        $depth = 0;

        for ($documentIndex = 0; $documentIndex < $nbDocuments; $documentIndex++) {
            $rand = rand(0, 100);

            switch ($action) {
                case 'none':
                    break;
                case 'child':
                    if ($depth + 1 <= $maxDepth) {
                        $currentPath = $this->inspector->getPath($lastDocument);
                        $depth++;
                    }
                    break;
                case 'parent':
                    if ($currentPath != $basePath && $depth - 1 <= $maxDepth) {
                        $currentPath = PathHelper::getParentPath($currentPath);
                        $depth--;
                    }
                    break;
            }

            $document = $this->createDocument($locales, $currentPath);
            $output->writeln(sprintf('#%d <info>%s</info> %s', $documentIndex, str_repeat('>', $depth + 1), $this->inspector->getName($document)));
            $lastDocument = $document;
            $action = 'none';
            if ($rand < $treeModProb) {
                $action = $treeActions[rand(1, count($treeActions) - 1)];
            }
        }
    }

    private function createDocument($locales, $path)
    {
        $title = uniqid();
        $page = $this->manager->create('page');
        $page->setStructureType('city');
        $page->setTitle($title);
        $page->setResourceSegment('/tree/' . $title);
        $page->getStructure()->bind(array(
            'article' => <<<EOT
The city of Carthage (/ˈkɑrθɪdʒ/; Arabic: قرطاج‎ Qarṭāj) is a city in Tunisia that was once the centre of the ancient Carthaginian civilization. The city developed from a Phoenician colony of the 1st millennium BC into the capital of an ancient empire.[2] The area of Carthage was before inhabited by Berber people who also became the bulk of Carthage's population and constituted a significant part of its army, economy and administration. Native Berbers and settling Phoenicians in Carthage mixed in different ways including religion and language, creating the Punic language and culture.
EOT
        ,
        ));

        foreach ($locales as $locale) {
            $this->manager->persist($page, $locale, array(
                'parent_path' => $path,
                'auto_create' => true,
            ));
        }

        $this->manager->flush();

        return $page;
    }
}
