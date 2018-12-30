<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 12/10/2018
 * Time: 11:29 AM
 */

namespace App\Tests\Security;


use App\Security\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase
{


    public function testTokenGenerator()
    {
        $token = new TokenGenerator();

        $generatedToken = $token->getRandomSecureToken(30);

        $this->assertEquals(30, strlen($generatedToken));

        //$this->assertEquals(1,preg_match("/[A-Za-z0-9]/", $generatedToken));

        $this->assertTrue(
            ctype_alnum($generatedToken),
            'Token contains incorrect character'
        );

    }

}