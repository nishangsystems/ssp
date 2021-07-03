<div class="form-group @error('name') has-error @enderror ">
    <label for="cname" class="control-label col-lg-2">Name <span style="color:red">*</span></label>
    <div class="col-lg-10">
        <input class=" form-control" name="name" value="{{old('name') ?? $income->name}}" type="text" required />
        @error('name')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>
<div class="form-group @error('amount') has-error @enderror">
    <label for="cname" class="control-label col-lg-2">Amount <span style="color:red">*</span></label>
    <div class="col-lg-10">
        <input class=" form-control" name="amount" value="{{old('amount') ?? $income->amount}}" type="number" required />
        @error('amount')
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