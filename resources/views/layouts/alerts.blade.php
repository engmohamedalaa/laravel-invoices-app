@if(session()->has('Add'))
<div class="alert alert-success alert-dismissable fade show" role="alert">
  <strong>{{ session()->get('Add') }}</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
@if(session()->has('Edit'))
<div class="alert alert-success alert-dismissable fade show" role="alert">
  <strong>{{ session()->get('Edit') }}</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
@if(session()->has('Delete'))
<div class="alert alert-success alert-dismissable fade show" role="alert">
  <strong>{{ session()->get('Delete') }}</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
@if(session()->has('Error'))
<div class="alert alert-danger alert-dismissable fade show" role="alert">
  <strong>{{ session()->get('Error') }}</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissable fade show" role="alert">
  <ul>
    @foreach ($errors->all() as $error)
        <li><strong>{{ $error }}</strong></li>
    @endforeach
  </ul>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
@if(session()->has('status_update'))
  <script>
    window.onload = function() {
        notif({
            msg: "تم تحديث حالة الدفع بنجاح",
            type: "success"
        })
    }
  </script>
@endif
