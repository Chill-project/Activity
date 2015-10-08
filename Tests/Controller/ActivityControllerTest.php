<?php

namespace Chill\ActivityBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Role\Role;

class ActivityControllerTest extends WebTestCase
{
    
    public function testAccessIsDeniedForUnauthenticated()
    {
        $client = $this->createClient();
        
        $crawler = $client->request('GET', sprintf('fr/person/%d/activity/', 
              $this->getPersonFromFixtures()->getId()));
        
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'),
              'the page does not redirect to http://localhost/login');
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
    private function getAuthenticatedClient()
    {
        return static::createClient(array(), array(
           'PHP_AUTH_USER' => 'center a_social',
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
}
