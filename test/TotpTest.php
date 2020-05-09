<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use sFire\OTP\Driver\Totp;

final class TotpTest extends TestCase {


    /**
     * Holds an instance of Totp
     * @var Totp
     */
    private Totp $totp;


    /**
     * Setup. Created new Totp instance
     * @return void
     */
    protected function setUp(): void {
        
        $this -> totp = new Totp();
        $this -> totp -> setSecret('ABCDEFGHIJK');
    }


    /**
     * Test if token can be generated by the current timestamp
     * @return void
     */
    public function testIfTokenCanBeGeneratedByCurrentTimestamp(): void {

        $this -> assertTrue(true === is_string($this -> totp -> now()));
        $this -> assertTrue(6 === strlen($this -> totp -> now()));
        $this -> assertRegExp('#[0-9]{6,6}#', $this -> totp -> now());
    }


    /**
     * Test if token can be generated by the current timestamp
     * @return void
     */
    public function testIfTokenCanBeGeneratedGivenTimestamp(): void {

        $this -> assertTrue(true === is_string($this -> totp -> timestamp(time())));
        $this -> assertTrue(6 === strlen($this -> totp -> timestamp(time())));
        $this -> assertRegExp('#[0-9]{6,6}#', $this -> totp -> timestamp(time()));
        $this -> assertEquals(240156, $this -> totp -> timestamp(1569941537));
    }


    /**
     * Test if a token can be verified
     * @return void
     */
    public function testIfTokenCanBeVerified(): void {

        $this -> assertTrue($this -> totp -> verify('240156', 1569941537));
        $this -> assertTrue($this -> totp -> verify($this -> totp -> timestamp(time())));
        $this -> assertFalse($this -> totp -> verify('012345', 1569941537));
    }


    /**
     * Test if a provisioning URL can be generated
     * @return void
     */
    public function testRetrievingProvisioningUrl(): void {
        $this -> assertTrue(true === is_string($this -> totp -> getProvisioningUrl('Accountname')));
    }


    /**
     * Test if multiple settings can be set
     * Test if the settings are working correctly
     * @return void
     */
    public function testSettingOptions(): void {

        $this -> totp -> setInterval(60);
        $this -> totp -> setAlgorithm('ripemd160');
        $this -> totp -> setDigits(8);

        $this -> assertTrue($this -> totp -> verify('30116834', 1569941537));
    }


    /**
     * Test if a secret key can be generated
     * @return void
     */
    public function testIfKeyCanBeGenerated(): void {

        $this -> assertRegExp('#[a-zA-Z234567]{16,16}#', $this -> totp -> generateSecret());
        $this -> assertRegExp('#[a-zA-Z234567]{48,48}#', $this -> totp -> generateSecret(48));
        $this -> assertRegExp('#[234567]{48,48}#', $this -> totp -> generateSecret(48, true, false, false));
        $this -> assertRegExp('#[a-z234567]{48,48}#', $this -> totp -> generateSecret(48, true, true, false));
        $this -> assertRegExp('#[a-zA-Z234567]{48,48}#', $this -> totp -> generateSecret(48, true, true, true));
    }
}