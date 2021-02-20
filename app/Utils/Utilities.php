<?php

namespace App\Utils;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use stdClass;

class Utilities
{
  static function getAgeTypeForRip($patientData)
  {
    $type = 1;
    $realAge = $patientData->age;
    if ($realAge == 0) {
      $realAge = $patientData->months;
      $type = 2;

      if ($realAge == 0) {
        $realAge = $patientData->days;
        $type = 3;
      }
    }

    return "$realAge,$type,";
  }

  static function buildLinks($items)
  {
    $links = new stdClass();

    $links->total = $items->total();
    $links->first = $items->url(1);
    $links->last  = $items->url($items->lastPage());
    $links->prev  = $items->previousPageUrl();
    $links->next  = $items->nextPageUrl();
    $links->page  = $items->currentPage();
    $links->perPage  = $items->perPage();

    return $links;
  }

  static function apiRequestLog()
  {
    try {
      $method = explode('@', class_basename(Route::currentRouteAction()))[1];
      self::infoLog($method . ' - request: ' . json_encode(Request::all()));
    } catch (Exception $e) {
      self::errorLog($e->getMessage());
    }
  }

  static function infoLog($message = '')
  {
    Log::channel('apiInfolog')->info($message);
  }

  static function errorLog($message = '')
  {
    Log::channel('apiErrorlog')->error($message);
  }

  static function ajaxRequestLog()
  {
    try {
      $method = explode('@', class_basename(Route::currentRouteAction()))[1];
      self::ajaxInfoLog($method . ' - request: ' . json_encode(Request::all()));
    } catch (Exception $e) {
      self::ajaxErrorLog($e->getMessage());
    }
  }

  static function ajaxInfoLog($message = '')
  {
    Log::channel('ajaxInfolog')->info($message);
  }

  static function ajaxErrorLog($message = '')
  {
    Log::channel('ajaxErrorlog')->error($message);
  }

  static function normalizeString($string)
  {
    $normalizeChars = array(
      'Š' => 'S', 'š' => 's', 'Ð' => 'Dj', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
      'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
      'Ï' => 'I', 'Ñ' => 'N', 'Ń' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
      'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
      'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
      'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ń' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
      'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'ƒ' => 'f', '#' => '', ' ' => '',
      'ă' => 'a', 'î' => 'i', 'â' => 'a', 'ș' => 's', 'ț' => 't', 'Ă' => 'A', 'Î' => 'I', 'Â' => 'A', 'Ș' => 'S', 'Ț' => 'T',
      'ù' => 'u', 'Ú' => 'U', '@' => '', '\\' => '', '/' => '',
    );

    return strtr($string, $normalizeChars);
  }
}
