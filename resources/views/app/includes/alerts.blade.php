@if(Session::has('danger'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Danger!</strong>
        <span>{{ Session::get('danger') }}</span>
    </div>
@endif
@if(Session::has('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Success!</strong>
        <span>{{ Session::get('success') }}</span>
    </div>
@endif
@if(Session::has('message'))
    <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span>{{ Session::get('message') }}</span>
    </div>
@endif