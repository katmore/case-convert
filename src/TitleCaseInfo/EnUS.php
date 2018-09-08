<?php
namespace CaseConvert\TitleCaseInfo;

use CaseConvert\TitleCaseInfo;

/**
 * "en-US" title-case information class
 */
class EnUS extends TitleCaseInfo {
   protected static function phraseLcase(string $phrase): bool {
   }
   
   protected static function maxPhraseLen(): int {
   }

   protected static function firstWordLcase(string $firstWord): bool {
      return false;
   }

   protected static function middleWordLcase(string $middleWord): bool {
      return true;
   }

   protected static function lastWordLcase(string $lastWord): bool {
      return false;
   }


}