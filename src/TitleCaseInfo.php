<?php
namespace CaseConvert;

/**
 * title-case information class
 */
abstract class TitleCaseInfo {
   
   private static function filterWord(string $word) {
      $word = explode(" ",trim($word));
      if (count($word)) return $word[0];
      return "";
   }
   
   /**
    * @private
    * @see \CaseConvert\TitleCaseInfo::getMaxPhraseLen()
    */
   abstract protected static function maxPhraseLen(): int;
   
   /**
    * Provides the maximum number of concurrent words in a title that need to be evaluated for casing.
    * @return int maximum number of concurrent words in a title that need to be evaluated for casing
    */
   final public static function getMaxPhraseLen(): int {
      return static::maxPhraseLen();
   }
   
   /**
    * @private
    * @see \CaseConvert\TitleCaseInfo::isPhraseLcase()
    */
   abstract protected static function phraseLcase(string $phrase): bool;
   
   /**
    * Determines if each word in a phrase of a title should always be lower-case unless it comprises the first word of a title.
    * @param string $phrase the phrase to evaluate
    * @return bool <i>true</i> if each word in a phrase of a title should always be lower-case unless it comprises the first word of a title, <b>false</b> otherwise.
    */
   final public static function isPhraseLcase(string $phrase): bool {
      if (""===($phrase = trim($phrase))) return false;
      return static::phraseLcase($phrase);
   }
   
   
   /**
    * @private
    * @see \CaseConvert\TitleCaseInfo::isFirstWordLcase()
    */
   abstract protected static function firstWordLcase(string $firstWord) : bool;
   
   /**
    * Determines if a word should always be lower-case when it is the first word in a title.
    * @param string $firstWord the word to evaluate
    * @return bool <b>true</b> if a word should always be lower-case when it is the first word in a title, <b>false</b> otherwise.
    */
   final public static function isFirstWordLcase(string $firstWord) : bool {
      if (""===($firstWord = self::filterWord($firstWord))) return false;
      return static::firstWordLcase($firstWord);
   }
   
   /**
    * @private
    * @see \CaseConvert\TitleCaseInfo::isLastWordLcase()
    */
   abstract protected static function lastWordLcase(string $lastWord) : bool;
   
   /**
    * Determines if a word should always be lower-case when it is the last word in a title.
    * @param string $lastWord the word to evaluate
    * @return bool <b>true</b> if a word should always be lower-case when it is the last word in a title, <b>false</b> otherwise.
    */
   final public static function isLastWordLcase(string $lastWord) : bool {
      if (""===($lastWord = self::filterWord($lastWord))) return false;
      return static::lastWordLcase($lastWord);
   }
   
   /**
    * @private
    * @see \CaseConvert\TitleCaseInfo::isMiddleWordLcase()
    */
   abstract protected static function middleWordLcase(string $middleWord) : bool;
   
   /**
    * Determines if a word should always be lower-case when it is niether the first nor the last word in a title.
    * @param string $middleWord the word to evaluate
    * @return bool <b>true</b> if a word should always be lower-case when it is niether the first nor the last word in a title, <b>false</b> otherwise.
    */
   final public static function isMiddleWordLcase(string $middleWord) : bool {
      if (""===($middleWord = self::filterWord($middleWord))) return false;
      return static::middleWordLcase($middleWord);
   }
}