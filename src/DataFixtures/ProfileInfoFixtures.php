<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Service\ProfileInfoService;
use App\Entity\ProfileInfo;
use App\Enum\GenderEnum;

class ProfileInfoFixtures extends Fixture implements DependentFixtureInterface
{
    public const GITHUB_LINK = 'https://github.com/';

    public const GITLAB_LINK = 'https://about.gitlab.com/';

    public const TELEGRAM_LINK = 'https://web.telegram.org/';

    public const SKYPE_LINK = 'https://www.skype.com/';

    public const PERSONAL_LINK = 'https://www.google.com/';
    
    public function load(ObjectManager $manager)
    {
        $admin = $this->getReference(UserFixtures::EMAIL_ADMIN);
        $adminInfo = new ProfileInfo();

        $adminInfo->setFirstname('Vasiliy');
        $adminInfo->setSurname('Pupkin');
        $adminInfo->setGender(GenderEnum::MALE);
        $adminInfo->setAge(25);
        $adminInfo->setJobPosition($this->getReference(JobPositionFixtures::PROJECT_MANAGER));
        $adminInfo->setCountry($this->getReference(CountryCityFixtures::COUNTRY_KAZAKHSTAN));
        $adminInfo->setCity($this->getReference(CountryCityFixtures::CITY_KARAGANDA));
        $adminInfo->setGithubLink(self::GITHUB_LINK);
        $adminInfo->setGitlabLink(self::GITLAB_LINK);
        $adminInfo->setTelegramLink(self::TELEGRAM_LINK);
        $adminInfo->setSkypeLink(self::SKYPE_LINK);
        $adminInfo->setPersonalLink(self::PERSONAL_LINK);
        $adminInfo->setUser($admin);
        $admin->setProfileInfo($adminInfo);
        
        $manager->persist($adminInfo);
        $manager->persist($admin);
        $manager->flush();

        $po = $this->getReference(UserFixtures::EMAIL_PO);
        $poInfo = new ProfileInfo();

        $poInfo->setFirstname('Vyacheslav');
        $poInfo->setSurname('Zaicev');
        $poInfo->setGender(GenderEnum::MALE);
        $poInfo->setAge(30);
        $poInfo->setJobPosition($this->getReference(JobPositionFixtures::PRODUCT_OWNER));
        $poInfo->setCountry($this->getReference(CountryCityFixtures::COUNTRY_KAZAKHSTAN));
        $poInfo->setCity($this->getReference(CountryCityFixtures::CITY_ALMATY));
        $poInfo->setGithubLink(self::GITHUB_LINK);
        $poInfo->setGitlabLink(self::GITLAB_LINK);
        $poInfo->setTelegramLink(self::TELEGRAM_LINK);
        $poInfo->setSkypeLink(self::SKYPE_LINK);
        $poInfo->setPersonalLink(self::PERSONAL_LINK);
        $poInfo->setUser($po);
        $po->setProfileInfo($poInfo);
        
        $manager->persist($poInfo);
        $manager->persist($po);
        $manager->flush();

        $customer = $this->getReference(UserFixtures::EMAIL_CUSTOMER);
        $customerInfo = new ProfileInfo();

        $customerInfo->setFirstname('Ekaterina');
        $customerInfo->setSurname('Rubkina');
        $customerInfo->setGender(GenderEnum::FEMALE);
        $customerInfo->setAge(28);
        $customerInfo->setJobPosition($this->getReference(JobPositionFixtures::PRODUCT_OWNER));
        $customerInfo->setCountry($this->getReference(CountryCityFixtures::COUNTRY_KAZAKHSTAN));
        $customerInfo->setCity($this->getReference(CountryCityFixtures::CITY_KARAGANDA));
        $customerInfo->setGithubLink(self::GITHUB_LINK);
        $customerInfo->setGitlabLink(self::GITLAB_LINK);
        $customerInfo->setTelegramLink(self::TELEGRAM_LINK);
        $customerInfo->setSkypeLink(self::SKYPE_LINK);
        $customerInfo->setPersonalLink(self::PERSONAL_LINK);
        $customerInfo->setUser($customer);
        $customer->setProfileInfo($customerInfo);
        
        $manager->persist($customerInfo);
        $manager->persist($customer);
        $manager->flush();

        $tm = $this->getReference(UserFixtures::EMAIL_TM);
        $tmInfo = new ProfileInfo();

        $tmInfo->setFirstname('Vladislav');
        $tmInfo->setSurname('Labinov');
        $tmInfo->setGender(GenderEnum::MALE);
        $tmInfo->setAge(32);
        $tmInfo->setJobPosition($this->getReference(JobPositionFixtures::TEAM_LEAD));
        $tmInfo->setCountry($this->getReference(CountryCityFixtures::COUNTRY_KAZAKHSTAN));
        $tmInfo->setCity($this->getReference(CountryCityFixtures::CITY_NUR_SULTAN));
        $tmInfo->setGithubLink(self::GITHUB_LINK);
        $tmInfo->setGitlabLink(self::GITLAB_LINK);
        $tmInfo->setTelegramLink(self::TELEGRAM_LINK);
        $tmInfo->setSkypeLink(self::SKYPE_LINK);
        $tmInfo->setPersonalLink(self::PERSONAL_LINK);
        $tmInfo->setUser($tm);
        $tm->setProfileInfo($tmInfo);
        
        $manager->persist($tmInfo);
        $manager->persist($tm);
        $manager->flush();

        $dev = $this->getReference(UserFixtures::EMAIL_DEV);
        $devInfo = new ProfileInfo();

        $devInfo->setFirstname('Kamila');
        $devInfo->setSurname('Orynova');
        $devInfo->setGender(GenderEnum::FEMALE);
        $devInfo->setAge(24);
        $devInfo->setJobPosition($this->getReference(JobPositionFixtures::DEVELOPER));
        $devInfo->setCountry($this->getReference(CountryCityFixtures::COUNTRY_KAZAKHSTAN));
        $devInfo->setCity($this->getReference(CountryCityFixtures::CITY_ALMATY));
        $devInfo->setGithubLink(self::GITHUB_LINK);
        $devInfo->setGitlabLink(self::GITLAB_LINK);
        $devInfo->setTelegramLink(self::TELEGRAM_LINK);
        $devInfo->setSkypeLink(self::SKYPE_LINK);
        $devInfo->setPersonalLink(self::PERSONAL_LINK);
        $devInfo->setUser($dev);
        $dev->setProfileInfo($devInfo);
        
        $manager->persist($devInfo);
        $manager->persist($dev);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            JobPositionFixtures::class,
            CountryCityFixtures::class,
            UserFixtures::class,
        ];
    }
}
