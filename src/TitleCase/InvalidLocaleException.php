<?php
namespace CaseConvert\TitleCase;

class InvalidLocaleException extends \RuntimeException {
   /**
    * Provides the specific problem that was encountered with a locale identifier.
    * @return the specific problem that was encountered with a locale identifier
    */
   public function getReason() : string {
      return $this->reason;
   }
   /**
    * Provides the locale identifier associated with this exception, should one exist.
    * @return string|null the locale identifier corresponding to this exception, or <b>null</b> if no locale identifier was associated.
    */
   public function getLocale() {
      return $this->locale;
   }
   private $reason;
   private $locale;
   public function __construct(string $reason,string $locale=null) {
      $this->reason = $reason;
      $this->locale = $locale;
      $msg = "invalid locale: $reason";
      if (!empty($locale)) {
         $msg .= ", locale: $locale";
      }
      parent::__construct($msg);
   }
}