<?php
class Fileparser {
    public $fileContents = NULL;
    public $dayAndTemperatures = array();
    
    public function __construct($file) {
        $this->fileContents = $this->readDatFile($file);
        $this->extractNeededValues();
    }
    
    public function readDatFile($file) {
        return file($file);
    }
    
    public function extractNeededValues() {
        foreach ($this->fileContents as $line) {
            preg_match('/^\s*\d+\s*\d+.\s*\d+./', $line, $elem);
            if (!empty($elem)) {
                $elem[0] = trim($elem[0]);
                $unfilteredValues = explode(' ', $elem[0]);

                // filter empty values and remove non digits (like asteriks)
                // @TODO: exclude!
                $values = array();
                foreach ($unfilteredValues as $value) {
                    if (!empty($value)) {
                        array_push($values, intval($value));
                    }
                }
                
                array_push($this->dayAndTemperatures, $values);
                unset($unfilteredValues, $value, $values, $elem);
            }
        }
        unset($line);
    }
    
    public function getDayWithSmallestTemperatureSpread() {
        $smallestSpread = 9999;
        $day = false;
        
        foreach ($this->dayAndTemperatures as $key => $values) {
            $spread = $this->calculateTemperatureSpread($key);
            if ($spread < $smallestSpread) {
                $smallestSpread = $spread;
                $day = $this->dayAndTemperatures[$key][0];
            }
        }
        
        return $day;
    }
    
    public function calculateTemperatureSpread($key) {
        $maxTemperature = $this->dayAndTemperatures[$key][1];
        $minTemperature = $this->dayAndTemperatures[$key][2];
        return $maxTemperature - $minTemperature;
    }

}
?>
