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
            preg_match('/^\s*\d+\s*\d+.\s*\d+./', $line, $lineMatched);
            if (!empty($lineMatched)) {
                $lineMatched[0]   = trim($lineMatched[0]);
                $unfilteredValues = explode(' ', $lineMatched[0]);
                $values           = $this->filterValues($unfilteredValues);
                array_push($this->dayAndTemperatures, $values);
                
                unset($unfilteredValues, $value, $values, $lineMatched);
            }
        }
        unset($line);
    }
    
    // filter empty values and remove non digits (like asteriks)
    public function filterValues($unfilteredValues) {
        $values = array();
        
        foreach ($unfilteredValues as $value) {
            if (!empty($value)) {
                // intval() is a bit dangerous here
                array_push($values, intval($value));
            }
        }
        unset($unfilteredValues, $value);
        
        return $values;
    }
    
    public function getDayWithSmallestTemperatureSpread() {
        $smallestSpread = 9999;
        $day = NULL;
        
        foreach ($this->dayAndTemperatures as $key => $values) {
            $spread = $this->calculateTemperatureSpread($key);
            if ($spread < $smallestSpread) {
                $smallestSpread = $spread;
                $day = $this->dayAndTemperatures[$key][0];
            }
        }
        unset($key, $values);
        
        return $day;
    }
    
    public function calculateTemperatureSpread($key) {
        $maxTemperature = $this->dayAndTemperatures[$key][1];
        $minTemperature = $this->dayAndTemperatures[$key][2];
        
        return $maxTemperature - $minTemperature;
    }
}
?>