<?php

namespace Modules\Icommerce\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class CartProductTransformer extends Resource
{
    public function toArray($request)
    {
        $data =  [
            'id' => $this->when($this->id, $this->id),
            'cart_id' => $this->when($this->cart_id, $this->cart_id),
            'product_id' => $this->when($this->product_id, $this->product_id),
            'name' => $this->when($this->nameproduct, $this->nameproduct),
            'price' => $this->when($this->price, $this->price),
            'subtotal' => $this->when($this->SubTotal, $this->SubTotal),
            'quantity' => $this->when($this->quantity, $this->quantity),
            //Relationshps Data
            'options' => CartProductOptionTransformer::collection($this->whenLoaded('cartproductoption')),
        ];

    return $data;
    }
}
