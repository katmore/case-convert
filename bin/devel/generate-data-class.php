#!/usr/bin/env php
<?php

exit((new class () {
   
   const ME_NAME = 'generate-data-class';
   const ME_DESC = 'CaseConvert\TitleCase "Data class" source file generator';
   const ME_USAGE = "[-hl] <LOCALE>";
   const ME_HELP =<<<ME_HELP
mode flags:
  -h,--help
    Print a help message and exit.
  -l,--list
    Print each available locale and exit.
    
arguments:
  <LOCALE>
    Specify the locale identifier of the "Data Class" to generate.
    Example: en-US
ME_HELP;
   const ME_COPYRIGHT = 'Copyright (c) 2012-2018 Doug Bird. All Rights Reserved.';
   
   const LOCALE_DATA_ROOT = __DIR__.'/locale-data';
   const VENDOR_AUTOLOAD = __DIR__.'/../../vendor/autoload.php';
   const BIN_VENDOR_AUTOLOAD = __DIR__.'/../../../../autoload.php';
   
   const HELP_MODE_ARGS = ['help','usage','about','version'];
   const LIST_MODE_ARGS = ['list','list-locales','list-locale',
            'locale','locales',];
   const HELP_MODE_OPTIONS = "huav";
   const LIST_MODE_OPTIONS = "l";
   public function __construct() {
      if (PHP_SAPI!=='cli') trigger_error("this script must be run from the command line",\E_USER_ERROR);
      if (is_file(static::VENDOR_AUTOLOAD)) {
         require static::VENDOR_AUTOLOAD;
      } else {
         if (is_file(static::BIN_VENDOR_AUTOLOAD)) {
            require static::BIN_VENDOR_AUTOLOAD;
         } else {
            static::printError("missing vendor/autoload.php, hint; have you run composer?");
            return $this->exitStatus = 1;
         }
      }
      $allowedShortOpt = "";
      $allowedShortOpt .= static::HELP_MODE_OPTIONS;
      $allowedShortOpt .= static::LIST_MODE_OPTIONS;
      $allowedLongOpt = [];
      $allowedLongOpt = array_merge($allowedLongOpt,static::HELP_MODE_ARGS);
      $allowedLongOpt = array_merge($allowedLongOpt,static::LIST_MODE_ARGS);
      
      $argv = static::enumArgv($allowedShortOpt,$allowedLongOpt);
      
      if ($this->exitStatus!==0) return;
      
      if (isset($argv[1])) {
         $arg1 = $argv[1];
      } else {
         $arg1 = null;
      }
      
      /*
       * apply "help" mode
       */
      if (
            (in_array($arg1,static::HELP_MODE_ARGS)) ||
            (false!==($opt = getopt(static::HELP_MODE_OPTIONS,static::HELP_MODE_ARGS)) && count($opt))
            )
      {
         static::printHelp();
         return;
      }
      
      /*
       * apply "list" mode
       */
      if (
            (in_array($arg1,static::LIST_MODE_ARGS)) ||
            (false!==($opt = getopt(static::LIST_MODE_OPTIONS,static::LIST_MODE_ARGS)) && count($opt))
            )
      {
         $locales = static::enumLocales();
         foreach($locales as $localeName) {
            static::printLine($localeName);
         }
         unset($localeName);
         return;
      }
      
      if (empty($arg1)) {
         static::printError("missing <LOCALE>");
         static::printUsage();
         return $this->exitStatus=2;
      }
      
   }
   
   private static function enumLocales() : array {
      $locales = [];
      foreach(array_diff(scandir(static::LOCALE_DATA_ROOT),['..','.']) as $f) {
         if (is_dir(static::LOCALE_DATA_ROOT . \DIRECTORY_SEPARATOR . $f)) {
            $locales []= $f;
         }
      }
      unset($f);
      return $locales;
   }
   
   private static function printHelp() : void {
      static::printLine(static::ME_DESC);
      static::printLine(static::ME_COPYRIGHT);
      static::printLine("");
      static::printUsage();
      static::printLine("");
      $help = static::ME_HELP;
      echo str_replace("\n",PHP_EOL,$help).PHP_EOL;
   }
   
   private static function printUsage() : void {
      echo "usage:".PHP_EOL;
      echo " ".static::ME_NAME." ".static::ME_USAGE.PHP_EOL;
   }
   
   private static function enumArgv(string $allowedShortOpt,array $allowedLongOpt) : array {
      $optind = 1;
      getopt("",[],$optind);
      
      /*
       * check for unrecognized options
       */
      $unrecognizedOption = false;
      if (!empty($_SERVER) && !empty($_SERVER['argv'])) {
         $allowedValueOpt = $requiredValueOpt = ['charset','set-default-charset'];
         $foundOptVal = [];
         foreach($requiredValueOpt as $longOptName) {
            $optf = getopt("",[$longOptName]);
            $longOptInd = 0;
            $optv = getopt("",["$longOptName:"],$longOptInd);
            if (isset($optv[$longOptName])) {
               $foundOptVal[]=$longOptName;
               if ($longOptInd>$optind) {
                  $optind=$longOptInd;
               }
            } else {
               if (isset($optf[$longOptName])) {
                  static::printError("option --$longOptName must have a value");
                  $unrecognizedOption = true;
               }
            }
         }
         unset($longOptName);
         
         
         foreach($_SERVER['argv'] as $a=>$v) {
            $longOptName = null;
            if ((substr($v,0,2)=='--') && (false!==strpos($v,'=')) ) {
               $o = explode("=",$v);
               if (empty($o[1])) {
                  $unrecognizedOption = true;
                  static::printError("unrecognized option: $v");
               } else {
                  $longOptName = substr($o[0],2);
                  if (!in_array($longOptName,$allowedLongOpt)) {
                     $unrecognizedOption = true;
                     static::printError("unrecognized option: $o[0]");
                  }
               }
            } else {
               if ((substr($v,0,2)=='--')) {
                  $longOptName = substr($v,2);
                  if (!in_array($longOptName,$allowedLongOpt)) {
                     $unrecognizedOption = true;
                     static::printError("unrecognized option: $v");
                  }
               } else {
                  if ((substr($v,0,1)=='-')) {
                     $optCheck = substr($v,1);
                     $optLen = strlen($optCheck);
                     for( $i = 0; $i < $optLen; $i++ ) {
                        $o = substr($optCheck,$i,1);
                        if ($o==='=') {
                           break 1;
                        }
                        if (false===strpos($allowedShortOpt,$o)) {
                           $unrecognizedOption = true;
                           static::printError("unrecognized option: -$o");
                        }
                     }
                     
                  }
               }
            }
         }
         unset($a);
         unset($v);
         
      }
      
      
      if ($unrecognizedOption) {
         return $this->exitStatus = 2;
      }
      
      $argOffset = $optind-1;
      $argv = [];
      if (!empty($_SERVER) && !empty($_SERVER['argv'])) {
         $argv = array_slice($_SERVER['argv'], $argOffset);
      }
      
      return $argv;
   }
   
   private $exitStatus = 0;
   public function getExitStatus() : int {
      return $this->exitStatus;
   }
   
   const PRINT_FLAG_PLAIN = 0;
   const PRINT_FLAG_NAME_PREFIX = 1;
   const PRINT_FLAG_VERBOSE_ONLY = 2;
   private static function printError(string $message,int $flags=self::PRINT_FLAG_NAME_PREFIX) : void {
      if ($flags & static::PRINT_FLAG_NAME_PREFIX) $message = static::ME_NAME.": $message";
      if (false===fwrite(STDERR,$message.PHP_EOL)) {
         echo $message.PHP_EOL;
      }
   }
   private static function printLine(string $message,int $flags=self::PRINT_FLAG_PLAIN) : void {
      if ($flags & static::PRINT_FLAG_NAME_PREFIX) $message = static::ME_NAME.": $message";
      echo $message.PHP_EOL;
   }
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
})->getExitStatus());