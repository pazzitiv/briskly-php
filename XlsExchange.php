<?php


use ExcelCreator\Creator;
use Exchanger\IExchanger;
use JsonParser\Parser;

class XlsExchange implements IExchanger
{
    protected string $jsonFile;
    protected string $xlsxFile;
    private string $xlsxFilename;
    protected string $ftpHost = '';
    protected string $ftpUsername = '';
    protected string $ftpPassword = '';
    protected string $ftpPath = '';
    private bool $useFTP = false;

    public function setInputFile(string $inFile): XlsExchange
    {
        $this->jsonFile = $inFile;

        return $this;
    }

    public function setOutputFile(string $outFile): XlsExchange
    {
        $this->xlsxFile = $outFile;
        $xlsxPathParts = explode('/', $this->xlsxFile) ?? ['items.xlsx'];
        $this->xlsxFilename = end($xlsxPathParts);

        return $this;
    }

    public function useFTP(): XlsExchange
    {
        $this->ftpHost = $_ENV['FTP_HOST'] ?? '';
        $this->ftpUsername = $_ENV['FTP_USERNAME'] ?? '';
        $this->ftpPassword = $_ENV['FTP_PASSWORD'] ?? '';
        $this->ftpPath = $_ENV['FTP_PATH'] ?? '';

        if(!empty($this->ftpHost) && !empty($this->ftpUsername) && !empty($this->ftpPassword) && !empty($this->ftpPath)) {
            $this->useFTP = true;
        }

        return $this;
    }

    public function export(): void
    {
        try {
            $writer = Creator::Create($this->xlsxFile, Parser::Parse($this->jsonFile));
            if($this->useFTP) {
                $writer->save($this->xlsxFile);

                $ftp = new FTP\Client($this->ftpHost, $this->ftpUsername, $this->ftpPassword, $this->ftpPath);
                $ftp->connect()
                    ->send($this->xlsxFile)
                    ->disconnect();

                unlink($this->xlsxFile);
            } else {
                #header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                #header('Content-Disposition: attachment; filename="' . $this->xlsxFilename . '"');
                #$writer->save('php://output');
                $writer->save($this->xlsxFile);
            }
        } catch(Exception $exception) {
            die("[{$exception->getCode()}] {$exception->getMessage()}");
        }
    }
}
