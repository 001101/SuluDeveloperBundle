<?php
/*
 * This file is part of the Sulu.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\DeveloperBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Sulu\Bundle\MediaBundle\Entity\Collection;
use Sulu\Bundle\MediaBundle\Entity\CollectionMeta;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Load fixture command
 */
class SuluFixtureGeneratorCommand extends ContainerAwareCommand
{

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @var ObjectManager
     */
    protected $manager;

    public function configure()
    {
        $this->setName('sulu:fixtures:generate:collections')
            ->setDescription('Generate fixtures')
            ->addOption(
                'collections',
                null,
                InputArgument::OPTIONAL,
                'Number of collections for the first level',
                30
            )
            ->addOption(
                'collection-nesting',
                null,
                InputArgument::OPTIONAL,
                'Collection nesting level',
                3
            )
            ->setHelp(
                <<<EOT
                The %command.name% command currently generates fixtures for collections & media.
EOT
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->faker = Factory::create();
        $this->manager = $this->getContainer()->get('doctrine')->getManager();

        $this->generateCollections(
            $input->getOption('collections'),
            $input->getOption('collection-nesting')
        );
    }

    protected function generateCollections($numberOfCollections, $nestingLevel)
    {
        $this->output->writeln('<info>Generate collections ...</info>');

        for ($i = 1; $i <= $numberOfCollections; $i++) {
            $this->generateCollection($numberOfCollections, $nestingLevel, 1);
            $this->manager->flush();
            $this->manager->clear();
        }

        $this->output->writeln('<info>... done</info>');
    }

    protected function generateCollection($numberOfCollections, $nestingLevel, $level, $parent = null)
    {
        $collection = new Collection();

        $meta = new CollectionMeta();
        $meta->setCollection($collection);
        $meta->setLocale('en');
        $meta->setTitle(
            ucfirst(
                $this->faker->words(
                    $this->faker->numberBetween(1, 3),
                    true
                )
            )
        );

        $collection->setType($this->manager->getRepository('SuluMediaBundle:CollectionType')->find(1));
        $collection->setDefaultMeta($meta);

        if (null !== $parent) {
            $collection->setParent($parent);
        }

        $this->manager->persist($collection);

        if ($level <= $nestingLevel) {
            for ($i = 0; $i < ($numberOfCollections / ($level + 1)); $i++) {
                $this->generateCollection($numberOfCollections, $nestingLevel, ($level + 1), $collection);
            }
        }
    }
}
