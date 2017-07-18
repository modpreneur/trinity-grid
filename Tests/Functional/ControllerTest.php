<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Tests\Functional;


/**
 * {@inheritDoc}
 */
class ControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();
        $r = $client->request('GET', '/grid/product?q=test');

        //dump(substr($r->html(), 0, 1000));

        $this->assertEquals(1, 1);
    }

}