<?php


use ExcelCreator\IExcelCreator;

class XlsExchange implements IExchanger
{
    protected string $jsonFile;
    protected string $xlsxFile;
    protected string $ftpHost;
    protected string $ftpUsername;
    protected string $ftpPassword;
    protected string $ftpPath;

    protected IExcelCreator $Creator;

    public function setInputFile(string $inFile): XlsExchange
    {
        $this->jsonFile = $inFile;

        return $this;
    }

    public function setOutputFile(string $outFile): XlsExchange
    {
        $this->xlsxFile = $outFile;

        return $this;
    }

    public function export(): void
    {
        // TODO: Implement send() method.
    }
}
