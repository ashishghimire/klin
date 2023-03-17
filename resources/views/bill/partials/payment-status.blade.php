@if($bill->payment_status == 'paid')
    <button type="button" class="btn btn-info btn-sm"
            disabled="disabled">{{$bill->payment_status}}</button>
@else
    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
            data-bs-target="#modal-{{$bill->id}}">{{$bill->payment_status}}</button>
@endif

<div class="modal fade" id="modal-{{$bill->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-5"> Estimate no. {{$bill->id}}</div>

                        <div class="col-md-5 ms-auto">
                            <small>{{!empty($bill->nepali_date) ? $bill->nepali_date : ''}}</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="align-self-md-auto">Customer: {{$bill->customer_name}}</div>

                    </div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            {!! Form::open(['route'=>['change-payment-status', $bill->id]]) !!}
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        Amount: {{$bill->amount}}
                    </div>
                    <div class="row">
                        {!! Form::select('payment_mode',  $paymentModes->pluck('name', 'name') , $bill->payment_mode, ['placeholder' => 'Not paid', 'class' => 'form-select form-select-sm payment', 'required']) !!}
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-outline-primary pay-button">Pay</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
