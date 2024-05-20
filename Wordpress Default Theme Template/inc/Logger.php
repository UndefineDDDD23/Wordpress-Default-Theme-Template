<?php
require_once __DIR__ . "/Info.php";

class Logger {    
    public function __construct() {}

    /**
     * Logging an exception in the theme folder.
     *
     * @param Throwable $exception - Logged exception.
     * @return void
    */
    static function log(Throwable $exception) {
        // Class defining the type of error in the log
        $exceptionType = get_class($exception);
    
        // Constructing the string trace
        $exceptionTraceArray = $exception -> getTrace();
        $exceptionTraceString = "[ ";
        foreach ($exceptionTraceArray as $key => $value) {            
            $exceptionTraceString = $exceptionTraceString . $value["file"] . " : " . $value["line"] ." -> ";
        }
        $exceptionTraceString .= " ]";
        

        $currentDate = date('d.m.Y h:i:s');
        $exceptionLine = $exception -> getLine();
        $exceptionFile = $exception -> getFile();
        $exceptionMessage = $exception -> getMessage();

        $logDestination = get_template_directory() . "/logs/theme.log";
        $resultLogMessage = "\n\n[ $currentDate ] [ $exceptionType ] [ $exceptionFile : $exceptionLine ] $exceptionTraceString  $exceptionMessage" ;
        file_put_contents($logDestination, $resultLogMessage, FILE_APPEND | LOCK_EX);
    }
}

?>