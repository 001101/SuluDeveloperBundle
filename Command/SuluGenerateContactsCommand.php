<?php

namespace Sulu\Bundle\DeveloperBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Sulu\Bundle\ContactBundle\Entity\Address;
use Sulu\Bundle\ContactBundle\Entity\AddressType;
use Sulu\Bundle\ContactBundle\Entity\Contact;
use Sulu\Bundle\ContactBundle\Entity\ContactAddress;
use Sulu\Bundle\ContactBundle\Entity\Country;
use Sulu\Bundle\ContactBundle\Entity\Email;
use Sulu\Bundle\ContactBundle\Entity\Fax;
use Sulu\Bundle\ContactBundle\Entity\Note;
use Sulu\Bundle\ContactBundle\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SuluGenerateContactsCommand extends ContainerAwareCommand
{
    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @var Phone[]
     */
    private $phoneTypes;

    /**
     * @var Email[]
     */
    private $emailTypes;

    /**
     * @var Fax[]
     */
    private $faxTypes;

    /**
     * @var AddressType[]
     */
    private $addressTypes;

    /**
     * @var Country[]
     */
    private $countries;

    public function configure()
    {
        $this->setName('sulu:fixtures:generate:contacts')
            ->setDescription('Generate fixtures')
            ->addOption(
                'amount',
                null,
                InputArgument::OPTIONAL,
                'Number of contacts',
                30
            )
            ->addOption(
                'locale',
                null,
                InputArgument::OPTIONAL,
                'locale for faker',
                'en_GB'
            )
            ->setHelp('The %command.name% command generates fixtures for contacts.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();

        $this->phoneTypes = $this->entityManager->getRepository('SuluContactBundle:PhoneType')->findAll();
        $this->emailTypes = $this->entityManager->getRepository('SuluContactBundle:EmailType')->findAll();
        $this->faxTypes = $this->entityManager->getRepository('SuluContactBundle:FaxType')->findAll();
        $this->addressTypes = $this->entityManager->getRepository('SuluContactBundle:AddressType')->findAll();
        $this->countries = $this->entityManager->getRepository('SuluContactBundle:Country')->findAll();

        $this->faker = Factory::create($input->getOption('locale'));

        for ($i = 0, $count = $input->getOption('amount'); $i < $count; $i++) {
            $contact = $this->generateContact();
            $this->entityManager->persist($contact);
        }

        $this->entityManager->flush();
    }

    public function generateContact()
    {
        $phone = new Phone();
        $phone->setPhone($this->faker->phoneNumber);
        $phone->setPhoneType($this->phoneTypes[array_rand($this->phoneTypes)]);
        $this->entityManager->persist($phone);
        $email = new Email();
        $email->setEmail($this->faker->email);
        $email->setEmailType($this->emailTypes[array_rand($this->emailTypes)]);
        $this->entityManager->persist($email);
        $fax = new Fax();
        $fax->setFax($this->faker->phoneNumber);
        $fax->setFaxType($this->faxTypes[array_rand($this->faxTypes)]);
        $this->entityManager->persist($fax);

        $contact = new Contact();
        $contact->setFirstName($this->faker->firstName);
        $contact->setLastName($this->faker->lastName);
        $contact->addPhone($phone);
        $contact->addEmail($email);
        $contact->addFax($fax);

        for ($i = 0, $count = rand(0, 5); $i <= $count; $i++) {
            $address = $this->createAddress($this->countries[array_rand($this->countries)]);
            $this->entityManager->persist($address);

            $contactAddress = new ContactAddress();
            $contactAddress->setAddress($address);
            $contactAddress->setContact($contact);
            $contactAddress->setMain($i === 0);
            $contact->addContactAddress($contactAddress);
            $address->addContactAddress($contactAddress);
            $this->entityManager->persist($contactAddress);
        }

        for ($i = 0, $count = rand(0, 10); $i <= $count; $i++) {
            $note = new Note();
            $note->setValue($this->faker->realText);
            $contact->addNote($note);
            $this->entityManager->persist($note);
        }
        $this->entityManager->persist($contact);

        return $contact;
    }

    private function createAddress(Country $country)
    {
        $address = new Address();
        $address->setTitle($this->faker->title);
        $address->setStreet($this->faker->streetName);
        $address->setNumber($number = $this->faker->buildingNumber);
        $address->setZip($zip = $this->faker->postcode);
        $address->setCity($city = $this->faker->city);
        $address->setState($this->faker->county);
        $address->setCountry($country);
        $address->setBillingAddress(true);
        $address->setPrimaryAddress(true);
        $address->setDeliveryAddress(false);
        $address->setPostboxCity($city);
        $address->setPostboxPostcode($zip);
        $address->setPostboxNumber($number);
        $address->setNote($this->faker->realText);
        $address->setAddressType($this->addressTypes[array_rand($this->addressTypes)]);

        return $address;
    }
}
