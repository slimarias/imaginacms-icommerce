<div id="cartLayout2" class="dropdown">

  @include("icommerce::frontend.livewire.cart.layouts.$layout.button")
  
  @if(isset($cart->id))
    @include('icommerce::frontend.livewire.cart.dropdown')
  @endif
</div>


@section('scripts-owl')
  <script>
    $(document).ready(function () {
      window.livewire.emit('refreshCart');
    });
  </script>
@stop