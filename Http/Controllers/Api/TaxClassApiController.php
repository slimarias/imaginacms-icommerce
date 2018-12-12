<?php

namespace Modules\Icommerce\Http\Controllers\Api;

// Requests & Response
use Modules\Icommerce\Http\Requests\TaxClassRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Base Api
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;

// Transformers
use Modules\Icommerce\Transformers\TaxClassTransformer;

class TaxClassApiController extends BaseApiController
{
  private $taxClass;
  
  public function __construct()
  {
    $this->taxClass = app('Modules\Icommerce\Repositories\TaxClassRepository');
  }
  
  /**
   * Display a listing of the resource.
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      //Request to Repository
      $taxClasses = $this->taxClass->index($this->getParamsRequest());
      
      //Response
      $response = ['data' => TaxClassTransformer::collection($taxClasses)];
      //If request pagination add meta-page
      $request->page ? $response['meta'] = ['page' => $this->pageTransformer($taxClasses)] : false;
      
    } catch (\Exception $e) {
      //Message Error
      $status = 500;
      $response = [
        'errors' => $e->getMessage()
      ];
    }
    return response()->json($response, $status ?? 200);
  }
  
  /** SHOW
   * @param Request $request
   *  URL GET:
   *  &fields = type string
   *  &include = type string
   */
  public function show($id, Request $request)
  {
    try {
      //Request to Repository
      $taxClass = $this->taxClass->show($id,$this->getParamsRequest());
      
      $response = [
        'data' => $taxClass ? new TaxClassTransformer($taxClass) : '',
      ];
      
    } catch (\Exception $e) {
      $status = 500;
      $response = [
        'errors' => $e->getMessage()
      ];
    }
    return response()->json($response, $status ?? 200);
  }
  
  /**
   * Show the form for creating a new resource.
   * @return Response
   */
  public function create(TaxClassRequest $request)
  {
    try {
      $taxClass = $this->taxClass->create($request->all());
      
      // sync table
      if ($taxClass)
        if (isset($request->rates))
          $taxClass->rates()->sync($request->rates);
        
      
      
      $response = ['data' => ''];
      
    } catch (\Exception $e) {
      $status = 500;
      $response = [
        'errors' => $e->getMessage()
      ];
    }
    return response()->json($response, $status ?? 200);
  }
  
  /**
   * Update the specified resource in storage.
   * @param  Request $request
   * @return Response
   */
  public function update($id, TaxClassRequest $request)
  {
    try {
      
      $taxClass = $this->taxClass->find($id);
      $taxClass = $this->taxClass->update($taxClass, $request->all());
      
      // sync tables
      if ($taxClass)
        if (isset($request->rates))
          $taxClass->rates()->sync($request->rates);
        else
          $taxClass->rates()->detach();
      
      $response = ['data' => ''];
      
    } catch (\Exception $e) {
      $status = 500;
      $response = [
        'errors' => $e->getMessage()
      ];
    }
    return response()->json($response, $status ?? 200);
  }
  
  /**
   * Remove the specified resource from storage.
   * @return Response
   */
  public function delete($id, Request $request)
  {
    try {
      $taxClass = $this->taxClass->find($id);
      $taxClass->delete();
      
      $response = ['data' => ''];
      
    } catch (\Exception $e) {
      $status = 500;
      $response = [
        'errors' => $e->getMessage()
      ];
    }
    return response()->json($response, $status ?? 200);
  }
}