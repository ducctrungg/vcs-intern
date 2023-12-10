<?php
const FILTERS = [
  'string' => [
    'filter' => FILTER_CALLBACK,
    'options' => 'htmlspecialchars'
  ],
  'email' => FILTER_SANITIZE_EMAIL,
  'int' => [
    'filter' => FILTER_SANITIZE_NUMBER_INT,
    'flags' => FILTER_REQUIRE_SCALAR
  ],
  'float' => [
    'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
    'flags' => FILTER_FLAG_ALLOW_FRACTION
  ],
  'url' => FILTER_SANITIZE_URL,
];

/**
 * Recursively trim strings in an array
 * @param array $items
 * @return array
 */
function array_trim(array $items): array
{
  return array_map(function ($item) {
    if (is_string($item)) {
      return trim($item);
    } elseif (is_array($item)) {
      return array_trim($item);
    } else
      return $item;
  }, $items);
}

/** 
 * Sanitize the inputs based on the rules an optionally trim the string
 * @param array $inputs
 * @param array $fields
 * @param array $filters FILTERS
 * @param bool $trim
 * @return array
 */
function sanitize(array $inputs, array $fields = [], array $filters = FILTERS, bool $trim = true): array
{
  if ($fields) {
    $options = array_map(fn($field) => $filters[$field], $fields);
    $data = filter_var_array($inputs, $options);
  } else {
    // Deprecated
    // $data = filter_var_array($inputs, $default_filter);
    $data = array_filter($inputs, 'htmlspecialchars');
  }

  return $trim ? array_trim($data) : $data;
}