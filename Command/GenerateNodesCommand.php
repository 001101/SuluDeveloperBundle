<?php
/*
 * This file is part of Sulu
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\DeveloperBundle\Command;

use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateNodesCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('sulu:developer:generate-nodes');
        $this->setDescription('Generate an arbitrary number of nodes for the content tree');
        $this->addArgument('webspaceKey', InputArgument::REQUIRED, 'The webspace in which the nodes should be created');
        $this->addArgument('number', InputArgument::REQUIRED, 'The number of nodes which whould be created');
        $this->addArgument('template', InputArgument::REQUIRED, 'The template for which data should be generated');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $webspaceKey = $input->getArgument('webspaceKey');
        $locale = 'en';
        $number = $input->getArgument('number');
        $templateKey = $input->getArgument('template');

        $faker = Factory::create('en');
        $contentMapper = $this->getContainer()->get('sulu.content.mapper');
        $template = $this->getContainer()->get('sulu.content.structure_manager')->getStructure($templateKey);

        for ($i = 0; $i < $number; $i++) {
            $data = array();

            foreach ($template->getProperties(true) as $property) {
                switch ($property->getContentTypeName()) {
                    case 'text_line':
                        $data[$property->getName()] = $faker->word;
                        break;
                    case 'text_editor':
                        $data[$property->getName()] = '<p>' . $faker->paragraph() . '</p>';
                        break;
                }
            }

            $contentMapper->save($data, $templateKey, $webspaceKey, $locale, 1);
        }
    }
}
