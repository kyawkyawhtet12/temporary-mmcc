@extends('layouts.master')

@section('content')

<div class="page-content">
  <div class="container-fluid">
    <div class="page-header">
      <h3 class="page-title">Withdrawal Form</h3>
    </div>
    <div class="card">
      <div class="card-body">
        @include('partials.msg')
        <form method="POST" action="{{ route('agent.withdrawal.store') }}">
          @csrf

          <span> Current Amount - {{ Auth::user()->amount }} </span>

          <div class="form-group mt-3">
            <label >Amount 
              @error('amount')
                <span class='text-danger'> {{ $message }}</span>
              @enderror
            </label>
            <input type="number" class="form-control" name="amount" placeholder="Amount">
          </div>
          <div class="form-group">
            <label >Payment Provider
              @error('payment_provider_id')
                <span class='text-danger'> {{ $message }}</span>
              @enderror
            </label>
            <select class="form-control" name="payment_provider_id">
              @foreach($providers as $provider)
              <option value="{{ $provider->id }}">{{ $provider->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label >Phone 
              @error('phone')
                <span class='text-danger'> {{ $message }}</span>
              @enderror
            </label>
            <input type="number" name="phone" class="form-control" placeholder="Phone">
          </div>
          <div class="form-group">
            <label >Remark
              @error('remark')
                <span class='text-danger'> {{ $message }}</span>
              @enderror
            </label>
            <textarea class="form-control" name="remark" rows="4"></textarea>
          </div>
          <button type="submit" class="btn btn-primary mr-2">Submit</button>
          <button class="btn btn-light">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection