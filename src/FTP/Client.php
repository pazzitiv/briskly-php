<?php


namespace FTP;


use Exception;

class Client
{
    private $cid;
    private string $ftpHost = '';
    private string $ftpUsername = '';
    private string $ftpPassword = '';
    private string $ftpPath = '';

    /**
     * Client constructor.
     * @param string $ftpHost
     * @param string $ftpUsername
     * @param string $ftpPassword
     * @param string $ftpPath
     */
    public function __construct(string $ftpHost, string $ftpUsername, string $ftpPassword, string $ftpPath)
    {
        $this->ftpHost = $ftpHost;
        $this->ftpUsername = $ftpUsername;
        $this->ftpPassword = $ftpPassword;
        $this->ftpPath = $ftpPath;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function connect(): Client
    {
        $this->cid = ftp_connect($this->ftpHost);

        if(!$this->cid) {
            throw new Exception("Ошибка соединения с {$this->ftpHost}.");
        }

        if(! $login_result = ftp_login($this->cid, $this->ftpUsername, $this->ftpPassword)) {
            throw new Exception("Ошибка аутентификации при соединении с {$this->ftpHost}.");
        }

        return $this;
    }

    /**
     * @param string $File
     * @return $this
     * @throws \Exception
     */
    public function send(string $File): Client
    {
        $PathParts = explode('/', $File) ?? ['items.xlsx'];
        $remoteFile = $this->ftpPath . '/' . end($PathParts);

        $uploaded = ftp_put($this->cid, $remoteFile, $File, FTP_BINARY);

        if (!$uploaded) {  // check upload status
            throw new Exception("Ошибка загрузки файла {$remoteFile}.");
        }

        return $this;
    }

    public function disconnect(): void
    {
        ftp_close($this->cid);
    }
}
