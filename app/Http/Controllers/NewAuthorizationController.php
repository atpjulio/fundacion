<?php

namespace App\Http\Controllers;

use App\Facades\AjaxResponse;
use App\Http\Requests\StoreNewAuthorizationRequest;
use App\Http\Requests\UpdateNewAuthorizationRequest;
use App\Models\Authorizations\Authorization;
use App\Models\Eps\Eps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NewAuthorizationController extends Controller
{
  /**
   * Authorization
   */

  public function getAuthorizations(Request $request)
  {
    $stored = $request->get('stored');
    $storedMessage = '';
    if ($stored) {
      $storedMessage = 'Autorización guardada exitosamente';
    }

    $updated = $request->get('updated');
    $updatedMessage = '';
    if ($updated) {
      $updatedMessage = 'Autorización guardada exitosamente';
    }

    return view('new-authorization.index', compact('stored', 'storedMessage', 'updated', 'updatedMessage'));
  }

  public function createAuthorization()
  {
    return view('new-authorization.create');
  }

  public function storeAuthorization(StoreNewAuthorizationRequest $request)
  {
    Authorization::storeRecord($request);

    Session::flash('message', 'EPS guardada exitosamente');
    return redirect()->route('new.eps.index');
  }

  public function editAuthorization($authorizationId)
  {
    return view('new-authorization.edit');
  }

  public function updateAuthorization(UpdateNewAuthorizationRequest $request, $epsId)
  {
    Authorization::updateRecord($request, $epsId);

    Session::flash('message', 'EPS actualizada exitosamente');
    return redirect()->route('new.eps.index');
  }

  /**
   * Authorization ajax
   */

  public function storeAjaxAuthorizations(Request $request)
  {
    $authorization = Authorization::storeRecord($request);

    return AjaxResponse::ok($authorization);
  }

  public function getAjaxAuthorizations(Request $request)
  {
    $authorizations = Authorization::getLatestRecords($request);
    $epss = Eps::getForSelect('authorizations');

    return AjaxResponse::okPaginated($authorizations, $request->get('links'), compact('epss'));
  }

  public function getAjaxAuthorization(Request $request, $authorizationId)
  {
    $authorization = Authorization::getRecord($request, $authorizationId);

    return AjaxResponse::ok($authorization);
  }

  public function deleteAjaxAuthorization($epsId)
  {
    Authorization::deleteRecord($epsId);

    return AjaxResponse::okPaginated();
  }
}
