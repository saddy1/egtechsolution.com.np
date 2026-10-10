<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\CreditPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string)$request->get('q',''));
        $status   = $request->get('status');
        $from_bs  = $request->get('from_bs');
        $to_bs    = $request->get('to_bs');

        // validate strict date format YYYY-MM-DD
        $from_bs = (is_string($from_bs) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $from_bs)) ? $from_bs : null;
        $to_bs   = (is_string($to_bs)   && preg_match('/^\d{4}-\d{2}-\d{2}$/', $to_bs))   ? $to_bs   : null;

        $credits = Credit::query()
            ->when($q, function($qb) use ($q) {
                $like = '%'.$q.'%';
                $qb->where(function($qq) use ($like) {
                    $qq->where('party_name','like',$like)
                       ->orWhere('contact','like',$like)
                       ->orWhere('address','like',$like)
                       ->orWhere('details','like',$like)
                       ->orWhere('credit_date_bs','like',$like)
                       ->orWhere('due_date_bs','like',$like);
                });
            })
            ->when(in_array($status, ['open','closed']), fn($qb) => $qb->where('status', $status))
            ->when($from_bs, fn($qb) => $qb->where('credit_date_bs','>=',$from_bs))
            ->when($to_bs, fn($qb) => $qb->where('credit_date_bs','<=',$to_bs))
            ->orderByRaw("CASE WHEN status='open' THEN 0 ELSE 1 END")
            ->orderByDesc('credit_date_bs')
            ->paginate(20)
            ->appends($request->only('q','status','from_bs','to_bs'));

        $totals = [
            'principal'   => (float) Credit::sum('principal_amount'),
            'outstanding' => (float) Credit::sum('outstanding_amount'),
        ];

        return view('backend.credits.index', compact('credits','q','status','from_bs','to_bs','totals'));
    }

    public function create()
    {
        return view('backend.credits.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'party_name'        => ['required','string','max:160'],
            'contact'           => ['nullable','string','max:60'],
            'address'           => ['nullable','string','max:160'],
            'details'           => ['nullable','string','max:255'],
            'principal_amount'  => ['required','numeric','min:0'],
            'credit_date_bs'    => ['required','string','max:15'], // BS only
            'due_date_bs'       => ['nullable','string','max:15'],
            'notes'             => ['nullable','string'],
        ]);

        $data['outstanding_amount'] = $data['principal_amount'];
        $data['status'] = 'open';

        Credit::create($data);

        return redirect()->route('credits.index')->with('success','Credit created successfully.');
    }

    public function show(Credit $credit)
    {
        $credit->load('payments');
        return view('backend.credits.show', compact('credit'));
    }

    public function addPayment(Request $request, Credit $credit)
    {
        $data = $request->validate([
            'amount'       => ['required','numeric','min:0.01'],
            'paid_date_bs' => ['required','string','max:15'], // BS only
            'reference'    => ['nullable','string','max:60'],
            'remarks'      => ['nullable','string','max:255'],
        ]);

        DB::transaction(function() use ($credit, $data){
            CreditPayment::create($data + ['credit_id' => $credit->id]);

            $credit->outstanding_amount = max(0, (float)$credit->outstanding_amount - (float)$data['amount']);
            if ($credit->outstanding_amount <= 0.00001) {
                $credit->outstanding_amount = 0;
                $credit->status = 'closed';
            }
            $credit->save();
        });

        return back()->with('success','Payment recorded.');
    }

    public function close(Credit $credit)
    {
        $credit->status = 'closed';
        $credit->save();
        return back()->with('success','Credit closed.');
    }
}
