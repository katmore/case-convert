# case-convert - ALPHA (NOT READY)
case coversion library

## Title Casing

### The title casing problem in PHP

PHP does not have any convenient "built-in" means of converting a text string value to "title case" format (also known as "proper case"). The few candidate functions in official PHP extensions do not properly adhere to any known English style guide for title casing. In fact, as demonstrated below, they do nothing different than the [`ucwords()`](https://php.net/ucwords) function!

Take the example of the phrase `i am the other one`, which SHOULD be `I am the Other One` when _properly_ title cased.

#### INCORRECT title casing with [`ucwords()`](https://php.net/ucwords)
```php
echo ucwords("i am the other one");
/* --output--
I Am The Other One
*/
```

#### INCORRECT title casing with `mb_convert_case()`
The [`mb_convert_case()`](https://php.net/mb_convert_case) function, part of the [*mbstring* extension](https://php.net/manual/en/ref.mbstring.php), produces the same identically incorrect results as [`ucwords()`](https://php.net/ucwords).
```php
echo mb_convert_case("i am the other one",MB_CASE_TITLE);
/* --output--
I Am The Other One
*/
```

#### INCORRECT title casing with `IntlBreakIterator::createTitleInstance()`
The [`IntlBreakIterator`](https://php.net/manual/en/class.intlbreakiterator.php) of the [*intl* extension](https://php.net/manual/en/book.intl.php) disapoints as well. Besides offering an absurdly tedious to interface to apply to this purpose, it ultimately provides incorrect results when using the `IntlBreakIterator::createTitleInstance()` method (identical to using [`ucwords()`](https://php.net/ucwords)).
```php
$text = "i am the other one";
$titleIterator = IntlBreakIterator::createTitleInstance("en-US");
$titleIterator->setText($text);
foreach($titleIterator as $boundary) {
        if (strlen($text)-1<$boundary) break 1;
        $text[$boundary] = strtoupper($text[$boundary]);
}
echo $text;
/* --output--
I Am The Other One
*/
```
