<?php
require dirname(__FILE__) . '/../Fileparser.php';

class FileparserTest extends PHPUnit_Framework_TestCase {
    private $fileparserObj;

    protected function setUp() {
        $this->fileparserObj = new Fileparser('../weather.dat', 7, 9, 38);
    }

    public function test__construct() {
        $this->assertInstanceof('Fileparser', $this->fileparserObj);
    }

    public function testGetDayNumberWithSmallestTemperatureSpread() {
        $this->assertEquals(14, $this->fileparserObj->getDayNumberWithSmallestTemperatureSpread());
    }

}
?>
