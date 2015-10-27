<?php

namespace Chill\ActivityBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\HttpFoundation\Response;

class ActivityControllerTest extends WebTestCase
{
    
    /**
     * @dataProvider getSecuredPagesUnauthenticated
     */
    public function testAccessIsDeniedForUnauthenticated($url)
    {
        $client = $this->createClient();

        $client->request('GET', $url);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'),
              sprintf('the page "%s" does not redirect to http://localhost/login', $url));
    }
    
    /**
     * 
     * @dataProvider getSecuredPagesAuthenticated
     * @param type $client
     * @param type $url
     */
    public function testAccessIsDeniedForUnauthorized($client, $url)
    {
        $client->request('GET', $url);

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
    
    public function getSecuredPagesAuthenticated()
    {
        static::bootKernel();
        
        $person = $this->getPersonFromFixtures();
        $activities = $this->getActivitiesForPerson($person);
        
        
        $user = $this->createFakeUser();  
        
        
        
        return array(
            array(
                $this->getAuthenticatedClient('center b_social'),
                sprintf('fr/person/%d/activity/', $person->getId())
            ),
            array(
                $this->getAuthenticatedClient('center b_social'),
                sprintf('fr/person/%d/activity/new', $person->getId())
            ),
            array(
                $this->getAuthenticatedClient('center b_social'),
                sprintf('fr/person/%d/activity/%d/show', $person->getId(), $activities[0]->getId())
            ),
            array(
                $this->getAuthenticatedClient('center b_social'),
                sprintf('fr/person/%d/activity/%d/edit', $person->getId(), $activities[0]->getId())
            ),
            array(
                $this->getAuthenticatedClient($user->getUsername()),
                sprintf('fr/person/%d/activity/new', $person->getId())
            )
        );
    }
    
    
    
    /**
     * Provide a client unauthenticated and 
     * 
     */
    public function getSecuredPagesUnauthenticated()
    {
        static::bootKernel();
        $person = $this->getPersonFromFixtures();
        $activities = $this->getActivitiesForPerson($person);
        
        return array(
            [ sprintf('fr/person/%d/activity/', $person->getId()) ],
            [ sprintf('fr/person/%d/activity/new', $person->getId()) ],
            [ sprintf('fr/person/%d/activity/%d/show', $person->getId(), $activities[0]->getId()) ],
            [ sprintf('fr/person/%d/activity/%d/edit', $person->getId(), $activities[0]->getId()) ],
        );
    }


    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = $this->getAuthenticatedClient();
        $person = $this->getPersonFromFixtures();

        // Create a new entry in the database
        $crawler = $client->request('GET', sprintf('en/person/%d/activity/',
              $person->getId()));
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 
              "Unexpected HTTP status code for GET /activity/");
        $crawler = $client->click($crawler->selectLink('Ajouter une nouvelle activité')
              ->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Ajouter une nouvelle activité')->form(array(
            'chill_activitybundle_activity'=> array(
               'date' => '15-01-2015',
               'durationTime' => array(
                    'hour' => '1',
                    'minute' => '30'
                ),
               'remark' => 'blabla',
               'scope' => $this->getRandomScope('center a_social', 'Center A')->getId(),
               'reason' => $this->getRandomActivityReason()->getId(),
               'type'   => $this->getRandomActivityType()->getId()
            )
        ));

        $client->submit($form);
        
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('dd:contains("January 15, 2015")')->count(), 
              'Missing element dd:contains("January 15, 2015")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink("Modifier l'activité")->link());
        
        $form = $crawler->selectButton("Sauver l'activité")->form(array(
            'chill_activitybundle_activity'  => array(
               'date' => '25-01-2015',
               'remark' => 'Foo'
            )
        ));

        $client->submit($form);
        
        $this->assertTrue($client->getResponse()->isRedirect());
        
        $crawler = $client->followRedirect();
        
        // check that new data are present
        $this->assertGreaterThan(0,
              $crawler->filter('dd:contains("January 25, 2015")')->count(),
              'Missing element dd:contains("January 25, 2015")');
        $this->assertGreaterThan(0,
              $crawler->filter('dd:contains("Foo")')->count(),
              'Missing element dd:contains("Foo")');
    }

    /**
     * 
     * @return \Symfony\Component\BrowserKit\Client
     */
    private function getAuthenticatedClient($username = 'center a_social')
    {
        return static::createClient(array(), array(
           'PHP_AUTH_USER' => $username,
           'PHP_AUTH_PW'   => 'password',
        ));
    }
    
    /**
     * 
     * @return \Chill\PersonBundle\Entity\Person
     */
    private function getPersonFromFixtures()
    {
        $em = static::$kernel->getContainer()
              ->get('doctrine.orm.entity_manager');
        
        $person = $em->getRepository('ChillPersonBundle:Person')
              ->findOneBy(array(
                 'firstName' => 'Depardieu',
                 'lastName' => 'Gérard'
              ));
        
        if ($person === NULL) {
            throw new \RuntimeException("We need a person with firstname Gérard and"
                  . " lastname Depardieu. Did you add fixtures ?");
        }
        
        return $person;
    }
    
    private function getActivitiesForPerson(\Chill\PersonBundle\Entity\Person $person)
    {
        $em = static::$kernel->getContainer()
              ->get('doctrine.orm.entity_manager');
        
        $activities = $em->getRepository('ChillActivityBundle:Activity')
                ->findBy(array('person' => $person));
        
        if (count($activities) === 0) {
            throw new \RuntimeException("We need activities associated with this "
                    . "person. Did you forget to add fixtures ?");
        }
        
        return $activities;
    }
    
    /**
     * 
     * @param string $username
     * @param string $centerName
     * @return \Chill\MainBundle\Entity\Scope
     */
    private function getRandomScope($username, $centerName)
    {
        $user = static::$kernel->getContainer()
              ->get('doctrine.orm.entity_manager')
              ->getRepository('ChillMainBundle:User')
              ->findOneByUsername($username);
        
        if ($user === NULL) {
            throw new \RuntimeException("The user with username $username "
                  . "does not exists in database. Did you add fixtures ?");
        }
        
        $center = static::$kernel->getContainer()
              ->get('doctrine.orm.entity_manager')
              ->getRepository('ChillMainBundle:Center')
              ->findOneByName($centerName);
        
        $reachableScopes = static::$kernel->getContainer()
              ->get('chill.main.security.authorization.helper')
              ->getReachableScopes($user, new Role('CHILL_ACTIVITY_UPDATE'), 
                    $center);
        
        return $reachableScopes[array_rand($reachableScopes)];
    }
    
    /**
     * 
     * @return \Chill\ActivityBundle\Entity\ActivityReason
     */
    private function getRandomActivityReason()
    {
        $reasons = static::$kernel->getContainer()
              ->get('doctrine.orm.entity_manager')
              ->getRepository('ChillActivityBundle:ActivityReason')
              ->findAll();
              
        return $reasons[array_rand($reasons)];
    }
    
    /**
     * 
     * @return \Chill\ActivityBundle\Entity\ActivityType
     */
    private function getRandomActivityType()
    {
        $types = static::$kernel->getContainer()
              ->get('doctrine.orm.entity_manager')
              ->getRepository('ChillActivityBundle:ActivityType')
              ->findAll();
        
        return $types[array_rand($types)];
    }
    
    /**
     * create a user without any permissions on CHILL_ACTIVITY_* but with 
     * permissions on center.
     * 
     * @return \Chill\MainBundle\Entity\User a fake user within a group without activity
     */
    private function createFakeUser()
    {
        $container = static::$kernel->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        
        //get the social PermissionGroup, and remove CHILL_ACTIVITY_*
        $socialPermissionGroup = $em
                ->getRepository('ChillMainBundle:PermissionsGroup')
                ->findOneByName('social');
        $withoutActivityPermissionGroup = (new \Chill\MainBundle\Entity\PermissionsGroup())
                ->setName('social without activity');
        //copy role scopes where ACTIVITY is not present
        foreach ($socialPermissionGroup->getRoleScopes() as $roleScope) {
            if (!strpos($roleScope->getRole(), 'ACTIVITY')) {
                $withoutActivityPermissionGroup->addRoleScope($roleScope);
            }
        }
        //create groupCenter        
        $groupCenter = new \Chill\MainBundle\Entity\GroupCenter();
        $groupCenter->setCenter($em->getRepository('ChillMainBundle:Center')
                ->findOneBy(array('name' => 'Center A')))
                ->setPermissionsGroup($withoutActivityPermissionGroup);
        $em->persist($withoutActivityPermissionGroup);
        $em->persist($groupCenter);
        
        //create user
        $faker = \Faker\Factory::create();
        $username = $faker->name;
        $user = new \Chill\MainBundle\Entity\User();
        $user
                ->setPassword($container->get('security.password_encoder')
                        ->encodePassword($user, 'password'))
                ->setUsername($username)
                ->addGroupCenter($groupCenter);
        
        $em->persist($user);
        
        $em->flush();
        
        return $user;
    }
}
