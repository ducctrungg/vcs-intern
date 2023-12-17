@if (Session::has('message'))
  <p class="alert {{ Session::get('alert-class', 'alert-info') }} fixed-top" style="width: 300px" id="flashMessage">{{ Session::get('message') }}</p>
@endif

