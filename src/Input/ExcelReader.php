<?php

namespace UtilityCli\Input;


class ExcelReader
{
    /**
     * @var \PHPExcel_Reader_Excel2007
     */
    private $phpExcelReader;

    /**
     * @var \PHPExcel
     */
    private $phpExcelObj;

    /**
     * ExcelReader constructor.
     * @param $filePath
     */
    public function __construct($filePath)
    {
        $this->phpExcelReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $this->phpExcelReader->setReadDataOnly(true);
        $this->phpExcelObj = $this->phpExcelReader->load($filePath);
    }

    /**
     * Read a excel sheet tab into an associative array.
     *
     * The empty cell will have the value of null in the array. The multi-value
     * cells (with |) will be broke into an array.
     *
     * @param string $tabName The tab name of the spreadsheet.
     * @param array|\Closure|null $filter The filter to filter the result.
     *   The filter can be a array with filtered key value pairs.
     *   It can also be a callback function which will process each element. It
     *   accept one parameter which is one element from the source array. And if
     *   it returns false, the element will be excluded from the filtered result.
     *
     * @return array
     */
    public function readInAssocArray($tabName, $filter = null, $sortBy = '')
    {
        $worksheet = $this->phpExcelObj->getSheetByName($tabName);
        $rawData = $worksheet->toArray(NULL, TRUE, TRUE, FALSE);
        $prepared = array();
        if (count($rawData) > 0) {
            for ($i = 1; $i < count($rawData); $i++) {
                $newRow = array();
                $isAllNull = true;
                for ($j = 0; $j < count($rawData[0]); $j++) {
                    if ($rawData[0][$j]) {
                        $arrayKey = $rawData[0][$j];
                        if (trim($rawData[$i][$j]) !== '') {
                            $newRow[$arrayKey] = trim($rawData[$i][$j]);
                            $isAllNull = false;
                        } else {
                            $newRow[$arrayKey] = null;
                        }
                    }
                }
                if (!empty($newRow) && !$isAllNull) {
                    array_push($prepared, $newRow);
                }
            }
        }
        // Apply filter
        If ($filter !== null) {
            $prepared = $this->filterArrayResult($prepared, $filter);
        }
        return $prepared;
    }

    /**
     * Filter the array.
     *
     * @param array $data The original array.
     * @param $criteria The filtering criteria. It can be a array or callback function.
     *
     * @return array The filtered array.
     */
    private function filterArrayResult(array $data, $criteria)
    {
        if (is_array($criteria)) {
            return array_filter($data, function($item) use ($criteria) {
                $match = TRUE;
                if ($criteria) {
                    foreach ($criteria as $key => $value) {
                        if (!(isset($item[$key]) && $item[$key] == $value)) {
                            $match = FALSE;
                            break;
                        }
                    }
                }
                return $match;
            });
        } elseif (is_callable($criteria) && $criteria instanceof \Closure) {
            return array_filter($data, $criteria);
        }
        return $data;
    }
}
