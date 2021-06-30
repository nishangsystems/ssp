@extends('admin.layout')
@section('section')
<div class="mx-3">
    <div class="form-panel">
        <form class="form-horizontal" role="form" method="POST" action="{{route('admin.boarding_fee.store')}}">

            @csrf
            <div class="form-group @error('amount_new_student') has-error @enderror">
                <label for="cname" class="control-label col-lg-2">Amount: New Students <span style="color:red">*</span></label>
                <div class="col-lg-10">
                    <input class=" form-control" name="amount_new_student" value="{{old('amount_new_student')}}" type="number" required />
                    @error('amount_new_student')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group @error('amount_old_student') has-error @enderror">
                <label for="cname" class="control-label col-lg-2">Amount: Old Students <span style="color:red">*</span></label>
                <div class="col-lg-10">
                    <input class=" form-control" name="amount_old_student" value="{{old('amount_old_student')}}" type="number" required />
                    @error('amount_old_student')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <div class="d-flex justify-content-end col-lg-12">
                    <button id="save" class="btn btn-xs btn-primary mx-3" type="submit">Save</button>
                    <a class="btn btn-xs btn-danger" href="#" type="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection