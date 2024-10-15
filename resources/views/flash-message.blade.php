@if ($message = Session::get('success'))
<!-- <div class="alert alert-success">
  <p></p>
</div> -->
<script>
  Toast.fire({
  icon: 'success',
  title: "{{ $message }}",
})
</script>
@endif

@if ($message = Session::get('error'))
<!-- <div class="alert alert-danger">
  <p>{{ $message }}</p>
</div> -->
<script>
  Toast.fire({
  icon: 'error',
  title: "{{ $message }}",
})
</script>
@endif
@if ($message = Session::get('middleware-error'))
<!-- <div class="alert alert-warning">
  <p>{{ $message }}</p>
</div> -->
<script>
  Toast.fire({
  icon: 'warning',
  title: "{{ $message }}",
})
</script>
@endif


@if ($errors->any())
<script>
  Toast.fire({
  icon: 'warning',
  title: "{{$errors->first()}}",
})
</script>
@endif
@if ($errors->has('email'))
    <script>
  Toast.fire({
  icon: 'warning',
  title: "{{ $errors->first('email') }}",
})
</script>
@endif

@push('scripts')
<script type="text/javascript">
    $("document").ready(function(){
    setTimeout(function(){
       $("div.alert").remove();
    }, 3500 ); // 5 secs

});
</script>
@endpush