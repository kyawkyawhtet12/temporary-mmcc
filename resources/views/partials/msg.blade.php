@if (count($errors)>0)
    @foreach ($errors->all() as $error)
        <!-- Warning Alert -->
        <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show" role="alert">
            <i class="ri-alert-line label-icon"></i><strong style="margin-left:5%">Warning</strong> {{ $error }}
        </div>
    @endforeach
@endif

@if (@session('success'))
    <!-- Success Alert -->
    <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
        <i class="ri-check-double-line label-icon"></i><strong style="margin-left:5%">Success</strong> {{ session('success') }}
    </div>
@endif

@if (@session('fail'))
    <!-- Danger Alert -->
    <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
        <i class="ri-error-warning-line label-icon"></i><strong style="margin-left:5%">Danger</strong> {{ session('fail') }}
    </div>
@endif



