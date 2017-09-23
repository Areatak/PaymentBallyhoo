<?php

class numToWord {

  protected $digit1 = array(
      0 => 'zero',
      1 => 'one',
      2 => 'two',
      3 => 'three',
      4 => 'four',
      5 => 'five',
      6 => 'six',
      7 => 'seven',
      8 => 'eight',
      9 => 'nine',
  );
  protected $digit1_5 = array(
      1 => 'eleven',
      2 => 'twelve',
      3 => 'thirteen',
      4 => 'fourteen',
      5 => 'fifteen',
      6 => 'sixteen',
      7 => 'seventeen',
      8 => 'eighteen',
      9 => 'nineteen',
  );
  protected $digit2 = array(
      1 => 'ten',
      2 => 'twenty',
      3 => 'thirty',
      4 => 'forty',
      5 => 'fifty',
      6 => 'sixty',
      7 => 'seventy',
      8 => 'eighty',
      9 => 'ninety'
  );
  protected $digit3 = array(
      1 => 'one hundred',
      2 => 'two hundred',
      3 => 'three hundred',
      4 => 'four hundred',
      5 => 'five hundred',
      6 => 'six hundred',
      7 => 'seven hundred',
      8 => 'eight hundred',
      9 => 'nine hundred',
  );
  protected $steps = array(
      1 => 'thousand',
      2 => 'million',
      3 => 'billion',
      4 => 'trillion',
      5 => 'quadrillion',
      6 => 'quintillion',
      7 => 'sextillion',
      8 => 'septillion',
      9 => 'octillion',
      10 => 'nonillion',
      11 => 'decillion',
  );
  protected $t = array(
      'and' => 'and',
  );

  function number_format($number, $decimal_precision = 0, $decimals_separator = '.', $thousands_separator = ',') {
    $number = explode('.', str_replace(' ', '', $number));
    $number[0] = str_split(strrev($number[0]), 3);
    $total_segments = count($number[0]);
    for ($i = 0; $i < $total_segments; $i++) {
      $number[0][$i] = strrev($number[0][$i]);
    }
    $number[0] = implode($thousands_separator, array_reverse($number[0]));
    if (!empty($number[1])) {
      $number[1] = $this->Round($number[1], $decimal_precision);
    }
    return implode($decimals_separator, $number);
  }

  protected function groupToWords($group) {
    $d3 = floor($group / 100);
    $d2 = floor(($group - $d3 * 100) / 10);
    $d1 = $group - $d3 * 100 - $d2 * 10;

    $group_array = array();

    if ($d3 != 0) {
      $group_array[] = $this->digit3[$d3];
    }

    if ($d2 == 1 && $d1 != 0) { // 11-19
      $group_array[] = $this->digit1_5[$d1];
    } else if ($d2 != 0 && $d1 == 0) { // 10-20-...-90
      $group_array[] = $this->digit2[$d2];
    } else if ($d2 == 0 && $d1 == 0) { // 00
    } else if ($d2 == 0 && $d1 != 0) { // 1-9
      $group_array[] = $this->digit1[$d1];
    } else { // Others
      $group_array[] = $this->digit2[$d2];
      $group_array[] = $this->digit1[$d1];
    }

    if (!count($group_array)) {
      return FALSE;
    }

    return $group_array;
  }

  public function numberToWords($number) {
    $formated = $this->number_format($number, 0, '.', ',');
    $groups = explode(',', $formated);

    $steps = count($groups);

    $parts = array();
    foreach ($groups as $step => $group) {
      $group_words = self::groupToWords($group);
      if ($group_words) {
        $part = implode(' ' . $this->t['and'] . ' ', $group_words);
        if (isset($this->steps[$steps - $step - 1])) {
          $part .= ' ' . $this->steps[$steps - $step - 1];
        }
        $parts[] = $part;
      }
    }
    return implode(' ' . $this->t['and'] . ' ', $parts);
  }

}