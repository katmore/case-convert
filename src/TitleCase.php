<?php
namespace CaseConvert;

class TitleCase {
   
   const FALLBACK_LOCALE='en-US';
   const TITLE_CASE_INFO_ROOT_NS = __NAMESPACE__."\\TitleCaseInfo";
   /**
    * Provides a locale name. 
    * 
    * <ul>
    *    <li>If <b>$locale</b> is not specified (has a <b>null</b> value), and the "intl" extension is loaded, the value from <b>Locale::getDefault()</b> will be used.</li>
    *    <li>If <b>$locale</b> is not specified (has a <b>null</b> value), and the "intl" extension is not loaded, "en-US" will be used.</li> 
    * </ul>
    * @param string $locale locale identifier
    * @return string locale identifier
    * @see \CaseConvert\TitleCase::FALLBACK_LOCALE
    * @see \Locale::getDefault()
    * @private
    */
   protected static function filterLocale(string $locale=null) : string {
      if (!empty($locale)) {
         return $locale;
      }
      if (extension_loaded("intl")) {
         return \Locale::getDefault();
      }
      return static::FALLBACK_LOCALE;
   }
   /**
    * Instantiates the TitleCaseInfo class corresponding to a locale. 
    * @param string $locale locale identifier
    * @throws \CaseConvert\TitleCase\InvalidLocaleException the TitleCaseInfo class corresponding to the specified locale does not exist
    * @return \CaseConvert\TitleCaseInfo TitleCaseInfo class corresponding to the specified locale
    * @private
    */
   protected static function loadInfo(string $locale) : TitleCaseInfo {
      $shortName = ucwords($locale,"-_ ");
      $shortName = str_replace(['-','_'," "],"",$shortName);
      $infoClassName = static::TITLE_CASE_INFO_ROOT_NS."\\$shortName";
      if (!class_exists($infoClassName)) {
         throw new TitleCase\InvalidLocaleException("locale not found",$locale);
      }
      return new $infoClassName;
   }
   /**
    * Converts a string to have proper title casing.
    * 
    * @param string $title value to convert
    * @param string $locale optional locale identifier
    * <ul>
    *    <li>If <b>$locale</b> is not specified (has a <b>null</b> value), and the "intl" extension is loaded, the value from <b>Locale::getDefault()</b> will be used.</li>
    *    <li>If <b>$locale</b> is not specified (has a <b>null</b> value), and the "intl" extension is not loaded, "en-US" will be used.</li> 
    * </ul>
    * @throws \CaseConvert\TitleCase\InvalidLocaleException the TitleCase "Info" class corresponding to the specified locale does not exist
    * @return string converted string with proper title casing
    */
   public static function convert(string $title,string $locale=null) : string {
      if (""===($title = trim($title))) return "";
      $locale = static::filterLocale($locale);
      $infoClass = static::loadInfo($locale);
      $word = explode(" ",$title);
      $wordCount = count($word);
      for($i=0;$i<$wordCount;$i++) {
         $w=$word[$i];
         $toUpper = true;
         if ($i===0) {
            $toUpper = !$infoClass->isFirstWordLcase($w);
         } else if ($i===($wordCount-1)) {
            $toUpper = !$infoClass->isLastWordLcase($w);
         } else {
            $toUpper = !$infoClass->isMiddleWordLcase($w);
         }
         if ($toUpper) {
            $word[$i] = ucfirst($w);
         }
      }
      unset($w);
      /**
       * @todo check if "phrases" within $title should be lower-case
       */
      $title = implode(" ",$word);
      return $title;
   }
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
}