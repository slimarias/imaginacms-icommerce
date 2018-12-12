<?php

namespace Modules\Icommerce\Http\Controllers\Api;

// Requests & Response
use Modules\Icommerce\Http\Requests\CouponRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Base Api
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;

// Transformers
use Modules\Icommerce\Transformers\CouponTransformer;

class CouponApiController extends BaseApiController
{
  private $coupon;
  
  public function __construct()
  {
    $this->coupon = app('Modules\Icommerce\Repositories\CouponRepository');
  }
  
  /**
   * Display a listing of the resource.
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      //Request to Repository
      $coupons = $this->coupon->index($this->getParamsRequest());
      
      //Response
      $response = ['data' => CouponTransformer::collection($coupons)];
      //If request pagination add meta-page
      $request->page ? $response['meta'] = ['page' => $this->pageTransformer($coupons)] : false;
      
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
      $coupon = $this->coupon->show($id,$this->getParamsRequest());
      
      $response = [
        'data' => $coupon ? new CouponTransformer($coupon) : '',
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
  public function create(CouponRequest $request)
  {
    try {
      $coupon = $this->coupon->create($request->all());
      
      // sync tables
      if ($coupon) {
        if (isset($request->categories))
          $coupon->categories()->sync($request->categories);
        
        if (isset($request->products))
          $coupon->products()->sync($request->products);
      }
      
      
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
  public function update($id, CouponRequest $request)
  {
    try {
      
      $coupon = $this->coupon->find($id);
      $coupon = $this->coupon->update($coupon, $request->all());
  
      // sync tables
      if ($coupon) {
        if (isset($request->categories))
          $coupon->categories()->sync($request->categories);
         else
          $coupon->categories()->detach();
      
        
        if (isset($request->products))
          $coupon->products()->sync($request->products);
         else
          $coupon->products()->detach();
        
      }
      
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
      $coupon = $this->coupon->find($id);
      $coupon->delete();
      
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