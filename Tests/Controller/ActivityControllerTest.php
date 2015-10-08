<?php

namespace Chill\ActivityBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
               'scope' => 1,
               'reason' => 2,
               'type'   => 3
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
}
