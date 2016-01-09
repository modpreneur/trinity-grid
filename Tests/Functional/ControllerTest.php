<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Tests\Functional;


class ControllerTest extends WebTestCase
{

    public function testIndex(){
        $client = $this->createClient();
        $r = $client->request('GET', '/grid1');

        //dump(substr($r->html(), 0, 1000));
    }

}