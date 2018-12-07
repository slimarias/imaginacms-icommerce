<div class="card card-block p-3 mb-3">
  <div class="row m-0">
    <div class="rounded-circle bg-primary text-white mr-3 d-flex align-items-center px-3 py-1">
      2
    </div>
    <h3 class="d-flex align-items-center">
      {{ trans('icommerce::billing_details.title') }}
    </h3>
  </div>

  <hr class="my-2"/>


  <a href="#" id="expandBillingDetails">{{ trans('icommerce::billing_details.form.expand_form') }}</a>

  <div class="showBilling" id="PaymentAddress" role="tablist" aria-multiselectable="true">
    <div class="card mb-0 border-0" v-if="addresses">
      <div class="card-header bg-white" role="tab" id="useExistingPayment">
        <label class="form-check-label">
          <input
            type="radio"
            class="form-check-input"
            name="existingOrNewPaymentAddress"
            id="existingPaymentAddress"
            value="1"
            data-parent="#PaymentAddress"
            data-toggle="collapse"
            data-target="#collapseExistingPayment"
            aria-expanded="true"
            aria-controls="collapseExistingPayment"
            v-model="useExistingOrNewPaymentAddress"
            checked>

          {{trans('icommerce::billing_details.address.old_address')}}

        </label>
      </div>

      <div id="collapseExistingPayment" class="collapse show" aria-labelledby="useExistingPayment" role="tabpanel">
        <select class="form-control"
                id=""
                name="selectBillingAddress"
                @change="changeAddress(selectedBillingAddress,1)"
                v-model="selectedBillingAddress"
        >
          <option v-for="(address, index) in selectAddresses" v-bind:value="index">@{{ address }}</option>

        </select>
      </div>
    </div>
    <div class="card mb-0 border-0">
      <div class="card-header bg-white" role="tab" id="useNewPayment" v-if="addresses">
        <label class="form-check-label">
          <input
            type="radio"
            class="form-check-input collapsed"
            name="existingOrNewPaymentAddress"
            id="newPaymentAddress"
            value="2"
            data-parent="#PaymentAddress"
            data-toggle="collapse"
            data-target="#collapseNewPayment"
            aria-expanded="true"
            aria-controls="collapseNewPayment"
            v-model="useExistingOrNewPaymentAddress"
          >

          {{trans('icommerce::billing_details.address.new_address')}}

        </label>
      </div>
      <div id="collapseNewPayment" :class="addresses ? 'collapse' : 'collapse show'" aria-labelledby="useNewPayment"
           role="tabpanel">

        <div class="form-group row">
          <div class="col pr-1">
            <label for="payment_firstname">{{ trans('icommerce::billing_details.form.first_name') }} </label>
            <input type="text" class="form-control" id="payment_firstname" name="payment_firstname"
                   v-model="billingData.firstname">

          </div>
          <div class="col pl-1">
            <label for="payment_lastname">{{ trans('icommerce::billing_details.form.last_name') }}</label>
            <input type="text" class="form-control" id="payment_lastname" name="payment_lastname"
                   v-model="billingData.lastname">
          </div>

        </div>

        <div class="form-group">
          <label for="payment_company">{{ trans('icommerce::billing_details.form.company') }}</label>
          <input type="text" class="form-control" id="payment_company" name="payment_company" aria-describedby="company"
                 v-model="billingData.company">
        </div>
        <div class="form-group">
          <label for="company">{{ trans('icommerce::billing_details.form.company_nit') }}</label>
          <input type="text"
                 class="form-control"
                 name="payment_nit"
                 v-mask="'###.###.###-N'"
                 v-model="billingData.nit"
                 >
        </div>

        <div class="form-group">
          <label for="payment_address_1">{{ trans('icommerce::billing_details.form.address1') }}</label>
          <input type="text" class="form-control mb-2" id="payment_address_1" name="payment_address_1"
                 v-model="billingData.address_1">
        </div>
        <div class="form-group">
          <label for="payment_address_2">{{ trans('icommerce::billing_details.form.address2') }}</label>
          <input type="text" class="form-control" id="payment_address_2" name="payment_address_2"
                 v-model="billingData.address_2">
        </div>

        <div class="form-group row">
          <div class="col pr-1">
            <label for="payment_city">{{ trans('icommerce::billing_details.form.city') }}</label>
            <input type="text" class="form-control" name="payment_city" id="payment_city" v-model="billingData.city">

          </div>
          <div class="col pl-1">
            <label for="payment_code">{{ trans('icommerce::billing_details.form.post_code') }}</label>
            <input type="number"
                   class="form-control"
                   name="payment_postcode"
                   id="payment_postcode"
                   v-model="billingData.postcode"
                   @change="getShippingMethods()">
          </div>

        </div>
        <div class="form-group">
          <label for="payment_country">{{ trans('icommerce::billing_details.form.country') }}</label>
          <select
            class="form-control"
            id="payment_country"
            name="payment_country"
            v-model="billingData.country"
            v-on:blur="getProvincesByCountry(billingData.country, 1)">
            <option value="null">Choose option</option>
            <option v-for="country in countries" v-bind:value="country.iso_2">@{{ country.name }}</option>
          </select>

        </div>


        <div class="form-group">
          <label for="payment_zone">{{ trans('icommerce::billing_details.form.state') }}</label>
          <select class="form-control"
                  id="payment_zone"
                  name="payment_zone"
                  v-model="billingData.zone"
                  @change="taxFlorida(billingData.zone,1)"
                  v-show="!statesBillingAlternative">
            <option v-for="state in statesBilling" v-bind:value="state.name">@{{ state.name }}</option>
            <option value="null">{{ trans('icommerce::billing_details.form.select_country') }}</option>
          </select>
          <input type="text"
                 class="form-control"
                 name="payment_zone"
                 id="payment_zone_alternative"
                 v-show="statesBillingAlternative"
                 v-model="billingData.zone"
          >
        </div>

        <div class="form-group">
          <label for="email">{{trans('icommerce::billing_details.form.email')}}</label>
          <input type="email"
                 class="form-control"
                 name="payment_email"
                 v-model="billingData.email"
                 >
        </div>
      </div>
    </div>

  </div>

</div>