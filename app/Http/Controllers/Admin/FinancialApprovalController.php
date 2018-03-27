<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FinancialApproval;
use App\Http\Requests\FinancialApprovalRequest;

class FinancialApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request);
        if ($request->ajax()) {
            //dd('is ajax');
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            $data['recordsTotal'] = FinancialApproval::count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = FinancialApproval::where(function ($query) use ($search) {
                    $query->where('file_no', 'LIKE', '%' . $search['value'] . '%');
                    //->orWhere('description', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = FinancialApproval::where(function ($query) use ($search) {
                    $query->where('file_no', 'LIKE', '%' . $search['value'] . '%');
                    //->orWhere('description', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = FinancialApproval::count();
                $data['data'] = FinancialApproval::skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            //dd(response()->json($data));
            $data['data'] = setRow_No($data['data']);
            return response()->json($data);
        }
        //$data['recordsTotal'] = $data['recordsFiltered']=Kind::all()->count();
        //   $data['data'] = Kind::all();
        //   $data['data'] = setRow_No($data['data']);
        return view('admin.financialapproval.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.financialapproval.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
