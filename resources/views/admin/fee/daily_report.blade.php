@extends('admin.layout')

@section('section')
    <div class="col-sm-12">

        <form class="input-group input-group-merge border">
            <input type="date" id="password" name="date" class="w-100 border-0" value="{{request('date')}}">
            <button type="submit" class="border-0" class="text-capitalize">{{__('text.word_refresh')}}</button>
        </form>

        <div class="content-panel">
            <div class="table-responsive">
                <table class="table table-bordered paginate">
                    <thead>
                    <tr class="text-capitalize">
                        <th>#</th>
                        <th>{{__('text.word_name')}}</th>
                        <th>{{__('text.word_class')}}</th>
                        <th>{{__('text.word_amount')}}</th>
                        @if(date('Y-m-d', time()) == date('Y-m-d', strtotime(request('date'))))
                        <th></th>
                        @endif
                    </tr>
                    </thead>
                    <tbody id="content">
                    @php($total = 0)
                        @foreach($fees as $k=>$fee)
                            @if($fee->student == null)
                                @continue
                            @endif
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{$fee->student->name}}</td>
                                <td>{{$fee->class->name}}</td>
                                <td>{{number_format($fee->amount)}} XAF</td>
                                @if(date('Y-m-d', time()) == date('Y-m-d', strtotime($fee->created_at)))
                                <td>
                                    <a onclick="event.preventDefault();
                                        document.getElementById('delete{{$fee->id}}').submit();" class=" btn btn-danger btn-xs m-2">{{__('text.word_delete')}}</a>
                                    <form id="delete{{$fee->id}}" action="{{route('admin.fee.destroy',$fee->id)}}" method="POST" style="display: none;">
                                        @method('DELETE')
                                        {{ csrf_field() }}
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @php($total += $fee->amount)
                        @endforeach
                        <tr class="text-capitalize">
                             <td colspan="3">{{__('text.word_total')}}</td>
                             <td>{{number_format(array_sum($fees->pluck('amount')->toArray()))}}</td>
                             @if(date('Y-m-d', time()) == date('Y-m-d', strtotime(request('date'))))
                                <td></td>
                             @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
