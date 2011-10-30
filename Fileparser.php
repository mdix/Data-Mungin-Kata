<?php
class Fileparser {
    private $fileContents;
    private $tableHeadlineLineNumber;
    private $fromLineNumber;
    private $untilLineNumber;
    private $blueprint;
    private $tableAsArray = array();
    
    public function __construct($path, $tableHeadlineLineNumber, $fromLineNumber, $untilLineNumber) {
        if (!is_file($path)) { 
            throw new Exception('No file at ' . $path);
        }

        $this->fileContents            = file($path);
        $this->tableHeadlineLineNumber = --$tableHeadlineLineNumber;
        $this->fromLineNumber          = --$fromLineNumber;
        $this->untilLineNumber         = --$untilLineNumber;

        $this->blueprint               = $this->buildBlueprint();
        $this->mapDataFromRows();
    }

    // build blueprint array
    private function buildBlueprint() {
        $headlinePartsUnfiltered = explode(' ', $this->fileContents[$this->tableHeadlineLineNumber]);
        $headlinePartsUnordered  = array_filter($headlinePartsUnfiltered);
        
        // need numeric values
        foreach ($headlinePartsUnordered as $key => $value) {
            $headlineParts[] = $value;
        }

        // values to keys
        $headlineParts = array_flip($headlineParts);
        return $headlineParts;
    }

    private function mapDataFromRows() {
        $start = $this->fromLineNumber;
        $line  = $this->fromLineNumber;
        $end   = $this->untilLineNumber;

        for ($start; $line <= $end; $line++) {
            $rowPartsUnfiltered = explode(' ', $this->fileContents[$line]);
            $rowPartsUnordered  = array_filter($rowPartsUnfiltered);
            $rowParts           = array();
            // need numeric values
            foreach ($rowPartsUnordered as $key => $value) {
                array_push($rowParts, $value);
            }

            $filledBlueprint = $this->blueprint;

            $i = 0;
            foreach ($filledBlueprint as $key => $value) {
                if (!empty($rowParts[$i])) {
                    $filledBlueprint[$key] = $rowParts[$i];
                    $i++;
                }
            }

            array_push($this->tableAsArray, $filledBlueprint);
        }
    }

    public function getDayNumberWithSmallestTemperatureSpread() {
        $day    = NULL;
        $spread = NULL;

        foreach ($this->tableAsArray as $key => $cellname) {
                $newSpread = $cellname['MxT'] - $cellname['MnT'];

                if ($spread === NULL || $spread > $newSpread) {
                    $spread = $newSpread;
                    $day    = $cellname['Dy'];
                }
        }
        
        return $day;
    }

}
?>
