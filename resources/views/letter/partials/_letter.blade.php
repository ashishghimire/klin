<div class="container">
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="card" style="width: 100%;">
        <div class="card-body">
            <p><span class="fw-bold">Date: </span> {{$letter->nepali_date}}</p>
            <br>
            <p class="fw-bold">To,</p>
            <p>{{$letter->to}}</p>
            <p>{{$letter->address}}</p>
            <br>
            <p class="text-center fw-bold">{{$letter->subject}}</p>
            <br>
            <p>{{$letter->body}}</p>
            <br>
            <p>{{$letter->signed_by}}</p>
            <p>{{$letter->designation}}</p>
            <p>Klin Laundromat Pvt. Ltd</p>
        </div>
    </div>
</div>
