@extends('admin.layout')

@section('section')

<div class="col-sm-12">
    <div class="col-lg-12">
        <div class="form-panel mb-5 ml-2">
            <form class="form-horizontal" role="form" method="POST" action="{{route('admin.pay_income.per_year')}}">
                <div class="form-group @error('class_id') has-error @enderror ">
                    <div class="col-sm-3">
                        <select class="form-control section" name="section_id">
                            <option value="">Select Section</option>
                            @foreach($school_units as $key => $unit)
                            <option value="{{$unit->id}}">{{$unit->name}}</option>
                            @endforeach
                        </select>
                        @error('section_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control Circle" id="circle" name="circle">
                            <option value="">Select Circle</option>
                        </select>
                        @error('circle')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control class" name="class_id">
                            <option value="">Select Class</option>
                        </select>
                        @error('class_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" name="batch_id">
                            <option value="">Select Year</option>
                            @foreach($years as $key => $year)
                            <option value="{{$year->id}}">{{$year->name}}</option>
                            @endforeach
                        </select>
                        @error('batch_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class=" col-sm-1 mb-1">
                        <button class="btn btn-xs btn-primary" id="submit" type="submit">Get Income</button>
                    </div>
                </div>
                @csrf
            </form>
        </div>
    </div>
    <div class="content-panel">
        <div class="adv-table table-responsive">
            <table cellpadding="0" cellspacing="0" border="0" class="table" id="hidden-table-info">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Income Type</th>
                        <th>Amount (CFA)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pay_incomes as $k=>$income)
                    <tr>
                        <td>{{$k+1}}</td>
                        <td>{{$income->student_name}}</td>
                        <td>{{$income->income_name}}</td>
                        <td>{{number_format($income->amount)}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                {{$pay_incomes->links()}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('.section').on('change', function() {

        let value = $(this).val();
        $.ajax({
            type: 'GET',
            url: '/admin/sections/' + value,
            success: function(response) {
                let size = response.data.length;
                let data = response.data;
                let html = "";
                if (size > 0) {
                    html += '<div><select class="form-control"  name="' + data[0].id + '" >';
                    html += '<option selected> Select Circle</option>'
                    for (i = 0; i < size; i++) {
                        html += '<option value=" ' + data[i].id + '">' + data[i].name + '</option>';
                    }
                    html += '</select></div>';
                } else {
                    html += '<div><select class="form-control"  >';
                    html += '<option selected> No data is avalaible</option>'
                    html += '</select></div>';
                }
                $('.circle').html(html);
            },
            error: function(e) {
                console.log(e)
            }
        })
    })
    $('#circle').on('change', function() {

        let value = $(this).val();
        $.ajax({
            type: 'GET',
            url: '/admin/classes/' + value,
            success: function(response) {
                let size = response.data.length;
                let data = response.data;
                let html = "";
                if (size > 0) {
                    html += '<div><select class="form-control"  name="' + data[0].id + '" >';
                    html += '<option selected> Select Class</option>'
                    for (i = 0; i < size; i++) {
                        html += '<option value=" ' + data[i].id + '">' + data[i].name + '</option>';
                    }
                    html += '</select></div>';
                } else {
                    html += '<div><select class="form-control"  >';
                    html += '<option selected> No data is avalaible</option>'
                    html += '</select></div>';
                }
                $('.class').html(html);
            },
            error: function(e) {
                console.log(e)
            }
        })
    })
</script>
@endsection