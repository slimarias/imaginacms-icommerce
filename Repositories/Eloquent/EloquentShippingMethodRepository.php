<?php

namespace Modules\Icommerce\Repositories\Eloquent;

use Modules\Icommerce\Repositories\ShippingMethodRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

use Illuminate\Http\Request;

//Support
use Modules\Icommerce\Support\Cart as cartSupport;
use Modules\Ihelpers\Events\CreateMedia;
use Modules\Ihelpers\Events\UpdateMedia;
use Illuminate\Support\Facades\Auth;

class EloquentShippingMethodRepository extends EloquentBaseRepository implements ShippingMethodRepository
{

    public function getItemsBy($params)
    {
        // INITIALIZE QUERY
        $query = $this->model->query();

        // RELATIONSHIPS
        $defaultInclude = [];
        $query->with(array_merge($defaultInclude, $params->include));

        // FILTERS
        if ($params->filter) {
            $filter = $params->filter;

            //add filter by search
            if (isset($filter->search)) {
                //find search in columns
                $query->where('id', 'like', '%' . $filter->search . '%')
                    ->orWhere('name', 'like', '%' . $filter->search . '%')
                    ->orWhere('updated_at', 'like', '%' . $filter->search . '%')
                    ->orWhere('created_at', 'like', '%' . $filter->search . '%');
            }

        }
        /*== FIELDS ==*/
        if (isset($params->fields) && count($params->fields))
            $query->select($params->fields);

        /*== REQUEST ==*/
        if (isset($params->page) && $params->page) {
            return $query->paginate($params->take);
        } else {
            $params->take ? $query->take($params->take) : false;//Take
            return $query->get();
        }
    }

    public function getItem($criteria, $params)
    {
        // INITIALIZE QUERY
        $query = $this->model->query();

        $query->where('id', $criteria);

        // RELATIONSHIPS
        $includeDefault = [];
        $query->with(array_merge($includeDefault, $params->include));

        // FIELDS
        if ($params->fields) {
            $query->select($params->fields);
        }
        return $query->first();

    }

    public function create($data)
    {

        $shippingMethod = $this->model->create($data);
        event(new CreateMedia($shippingMethod, $data));
        return $shippingMethod;

    }

    public function update($model, $data)
    {
      
        // init
        $options['init'] = $model->options->init;

        // Extra Options
        foreach ($model->options as $key => $value) {
            if ($key != "init") {
                $options[$key] = $data[$key];
                unset($data[$key]);
            }
        }

        $data['options'] = $options;

        $model->update($data);
        event(new UpdateMedia($model, $data));
        // Sync Data
        $model->geozones()->sync(array_get($data, 'geozones', []));

        return $model;

    }


    /**
     *
     * @param $request
     * @return Response
     */

    public function getCalculations($request, $params)
    {

      /* Init query */
      $query = $this->model->query();

      /* Check actives */
      $query->where("status", 1);

      /* Filters */
      if (isset($params->filter) && $params->filter) {
        $filter = $params->filter;

        if (isset($filter->geozones)) {
          $query->whereIn("geozone_id", $filter->geozones);
        }
      }

      /* Run query*/
      $methods = $query->get();

      if (isset($methods) && count($methods) > 0) {
        // Search Cart
        $cartRepository = app('Modules\Icommerce\Repositories\CartRepository');
        if (isset($data->products['cart_id'])) {
            $cart = $cartRepository->find($request->products['cart_id']);
            // Fix data cart products
            $supportCart = new cartSupport();
            $dataCart = $supportCart->fixProductsAndTotal($cart);
            // Add products to request
            $data['products'] = $dataCart['products'];
        }
        foreach ($methods as $key => $method) {
          try {
            $results = app($method->options->init)->init($request);
            $resultData = $results->getData();
            $method->calculations = $resultData;
          } catch (\Exception $e) {
            $resultData["msj"] = "error";
            $resultData["items"] = $e->getMessage();
            $method->calculations = $resultData;
          }
        }
      }
      return $methods;
    }

}
