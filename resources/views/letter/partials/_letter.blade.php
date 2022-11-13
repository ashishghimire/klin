<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="card" style="width: 100%;">
            <div class="card-body">
                <p><span class="fw-bold">Date: </span> {{$letter->nepali_date}}</p>
                <br>
                <p class="fw-bold">To,</p>
                <p>{{$letter->to}}</p>
                <p>{{$letter->address}}</p>
                <br>
                <p>Subject: {{$letter->subject}}</p>
                <br>
                <p>{{$letter->body}}</p>
                <br>
                <p>{{$letter->signed_by}}</p>
                <p>{{$letter->designation}}</p>
                <p>Klin Laundromat Pvt. Ltd</p>
            </div>
        </div>
    </div>
</div>
