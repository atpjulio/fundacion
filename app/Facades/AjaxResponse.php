<?php

namespace App\Facades;

use App\Utils\AjaxError;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use stdClass;

class AjaxResponse
{
  static function ok($arrayResult = [], $httpCode = 200, $links = null, $included = null)
  {
    $route  = Route::current();
    $input  = $route->methods()[0] . ': /' . $route->uri;

    if (is_array($arrayResult)) {
      $result = $arrayResult;
    } elseif ($arrayResult instanceof Collection) {
      $result = $arrayResult->toArray();
    } else {
      $result = [$arrayResult];
    }

    return response()->json([
      'count'    => count($result),
      'included' => $included,
      'input'    => $input,
      'result'   => $result,
      'links'    => $links,
    ], $httpCode);
  }

  static function okPaginated($arrayResult = [], $links = null, $included = null)
  {
    return self::ok($arrayResult, 200, $links, $included);
  }

  static function error(AjaxError $arrayError, $httpCode = 400)
  {
    $route  = Route::current();
    $errors = is_array($arrayError) ? $arrayError : [$arrayError];

    foreach ($errors as $error) {
      $source = new stdClass();
      $links  = new stdClass();

      $source->pointer   = $route->methods()[0] . '/' . $route->uri;
      $source->parameter = implode(',', $route->parameters());

      $links->about = '';//config('constants.apiDocs');

      $error->source = $source;
      $error->links  = $links;
    }

    return response()->json([
      'errors' => $errors,
    ], $httpCode);
  }

  static function error500($errorCode = 'E0000', $detail = '')
  {
    return self::error(AjaxError::setCode($errorCode, $detail), 500);
  }

  static function errorByCode($errorCode = 'E0000', $httpErrorCode = 400, $detail = '')
  {
    return self::error(AjaxError::setCode($errorCode, $detail), $httpErrorCode);
  }

  static function validationError($detail = [], $httpErrorCode = 422)
  {
    return self::error(AjaxError::setCode('E0004', $detail), $httpErrorCode);
  }
}