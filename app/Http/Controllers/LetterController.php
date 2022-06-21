<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Nilambar\NepaliDate\NepaliDate;

class LetterController extends Controller
{
    /**
     * @var NepaliDate
     */
    protected $nepaliDate;
    /**
     * @var Letter
     */
    private $letter;

    /**
     * Display a listing of the resource.
     *
     * @param NepaliDate $nepaliDate
     * @param Letter $letter
     */

    public function __construct(NepaliDate $nepaliDate, Letter $letter)
    {
        $this->nepaliDate = $nepaliDate;
        $this->letter = $letter;
        $this->middleware('auth');
        $this->middleware('isAdmin',['only' => ['edit', 'update', 'destroy']]);
    }

    public function index()
    {
        $letters = Letter::all();

        return view('letter.index', compact('letters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $today = $this->todaysNepaliDate();

        return view('letter.create', compact('today'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['ref_no'] = $this->generateRefNo();
        $data['user_id'] = auth()->user()->id;

        try {
            $letter = $this->letter->create($data);

            DB::commit();
            return redirect()->route('letter.index');

        } catch (\Exception $e) {
            dd($e);// This is for debugging purpose only. Remove it!!
            DB::rollback();
            return redirect()->back()->withErrors('There was a problem in creating letter');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Letter $letter
     * @return \Illuminate\Http\Response
     */
    public function show(Letter $letter)
    {
        return view('letter.show', compact('letter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Letter $letter
     * @return \Illuminate\Http\Response
     */
    public function edit(Letter $letter)
    {
        return view('letter.edit', compact('letter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Letter $letter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Letter $letter)
    {
        if (!$letter->update($request->all())) {
            return redirect()->back()->withErrors('There was a problem in updating letter');
        }

        return redirect()->route('letter.index')->withSuccess("letter successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Letter $letter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Letter $letter)
    {
        if ($letter->delete()) {
            return redirect()->route('letter.index')->withSuccess("letter successfully deleted");
        } else {
            return redirect()->back()->withErrors('There was a problem in deleting letter');
        }
    }


    public function todaysNepaliDate()
    {
        $englishDate = Carbon::now();
        $year = $englishDate->format('Y');
        $month = $englishDate->format('m');
        $day = $englishDate->format('d');
        $nepaliDateArray = $this->nepaliDate->convertAdToBs($year, $month, $day);
        $nepaliDate = $nepaliDateArray['year'] . '-' . $nepaliDateArray['month'] . '-' . $nepaliDateArray['day'];
        return $nepaliDate;
    }

    public function generateRefNo()
    {
        $englishDate = Carbon::now();
        $year = $englishDate->format('Y');
        $month = $englishDate->format('m');
        $day = $englishDate->format('d');
        $nepaliDateArray = $this->nepaliDate->convertAdToBs($year, $month, $day);
        $statement = DB::select("show table status like 'letters'");

        return $nepaliDateArray['year'] . sprintf("%02d", $nepaliDateArray['month']) . sprintf("%02d", $nepaliDateArray['day']) . sprintf("%02d", $statement[0]->Auto_increment);
    }

    public function download($id)
    {
        $letter = Letter::find($id);
        $dompdf = new Dompdf();
        $html = view('letter.partials._letter', compact('letter'));
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Output the generated PDF (1 = download and 0 = preview)
        $dompdf->stream("letter",array("Attachment"=>0));


//        $pdf = PDF::loadView('letter.partials._letter', compact('letter'));
//        return $pdf->download('letter.pdf');
    }

}
