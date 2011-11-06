<?php
require dirname(__FILE__) . '/../Fileparser.php';

class FileparserTest extends PHPUnit_Framework_TestCase {
    private $fileparserObj;

    protected function setUp() {
        $this->fileparserObj = new Fileparser('../weather.dat');
    }

    public function testReadDatFile() {
        $this->assertNotNull($this->fileparserObj->fileContents);
        $this->assertInternalType('array', $this->fileparserObj->fileContents);
    }
    
    public function testExtractNeededValues() {
        // wanna have a two dimensional array
        $this->assertInternalType('array', $this->fileparserObj->dayAndTemperatures);
        $this->assertInternalType('array', $this->fileparserObj->dayAndTemperatures[0]);
        
        $this->assertEquals(1, $this->fileparserObj->dayAndTemperatures[0][0]);   // day 1
        $this->assertEquals(88, $this->fileparserObj->dayAndTemperatures[0][1]);  // max
        $this->assertEquals(59, $this->fileparserObj->dayAndTemperatures[0][2]);  // min
        
        $this->assertEquals(13, $this->fileparserObj->dayAndTemperatures[12][0]); // day 13
        $this->assertEquals(70, $this->fileparserObj->dayAndTemperatures[12][1]); // max
        $this->assertEquals(59, $this->fileparserObj->dayAndTemperatures[12][2]); // min

        $this->assertEquals(9, $this->fileparserObj->dayAndTemperatures[8][0]); // day 9
        $this->assertEquals(86, $this->fileparserObj->dayAndTemperatures[8][1]); // max
        $this->assertEquals(32, $this->fileparserObj->dayAndTemperatures[8][2]); // min (has a asteriks)

        $this->assertEquals(26, $this->fileparserObj->dayAndTemperatures[25][0]); // day 26
        $this->assertEquals(97, $this->fileparserObj->dayAndTemperatures[25][1]); // max (has a asteriks)
        $this->assertEquals(64, $this->fileparserObj->dayAndTemperatures[25][2]); // min
        
        $this->assertEquals(30, $this->fileparserObj->dayAndTemperatures[29][0]); // day 30 (last day)
        $this->assertEquals(90, $this->fileparserObj->dayAndTemperatures[29][1]); // max
        $this->assertEquals(45, $this->fileparserObj->dayAndTemperatures[29][2]); // min
        
        $this->assertArrayNotHasKey(30, $this->fileparserObj->dayAndTemperatures); // proper end
    }
    
    public function testFilterValues() {
        $this->assertInternalType('array', $this->fileparserObj->filterValues(array()));
        
        $this->assertArrayHasKey(0, $this->fileparserObj->filterValues(array("", "", "1", "", "33", "20", "")));
        $this->assertArrayHasKey(1, $this->fileparserObj->filterValues(array("", "", "1", "", "33", "20", "")));
        $this->assertArrayHasKey(2, $this->fileparserObj->filterValues(array("", "", "1", "", "33", "20", "")));
        $this->assertArrayNotHasKey(3, $this->fileparserObj->filterValues(array("", "", "1", "", "33", "20", "")));
        
        $this->assertContains(1, $this->fileparserObj->filterValues(array("", "", "1", "", "33", "20", "")));
        $this->assertContains(33, $this->fileparserObj->filterValues(array("", "", "1", "", "33", "20", "")));
        $this->assertContains(20, $this->fileparserObj->filterValues(array("", "", "1", "", "33", "20", "")));
    }

    public function testGetDayWithSmallestTemperatureSpread() {
        $this->assertEquals(14, $this->fileparserObj->getDayWithSmallestTemperatureSpread()); // should return day 14
    }
    
    public function testCalculateTemperatureSpread() {
        $this->assertEquals(16, $this->fileparserObj->calculateTemperatureSpread(1)); // day 2
        $this->assertEquals(20, $this->fileparserObj->calculateTemperatureSpread(5)); // day 6
        $this->assertEquals(33, $this->fileparserObj->calculateTemperatureSpread(25)); // day 26
        $this->assertEquals(45, $this->fileparserObj->calculateTemperatureSpread(29)); // day 30
    }
}
?>