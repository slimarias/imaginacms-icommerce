<?php

namespace Modules\Icommerce\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class OptionValueRequest extends BaseFormRequest
{
  public function rules()
  {
    return [
      'option_id' => 'required',
  
    ];
  }
  
  public function translationRules()
  {
    return [
      'description' => 'required|min:2',
    ];
  }
  
  public function authorize()
  {
    return true;
  }
  
  public function messages()
  {
    return [
      // option id
      'option_id.required' => trans('icommerce::common.messages.field required'),
    
      // sort_order
      'sort_order.required' => trans('icommerce::common.messages.field required'),
  
    ];
  }
  
  public function translationMessages()
  {
    return [
      // description
      'description.required' => trans('icommerce::common.messages.field required'),
      'description.min:2' => trans('icommerce::common.messages.min 2 characters'),
    ];
  }
  
  public function getValidator(){
    return $this->getValidatorInstance();
  }
}
