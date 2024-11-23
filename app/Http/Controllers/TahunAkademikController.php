<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TahunAkademikController extends Controller
{
    public function semester(Request $request)
    {
        dd($request->all());
    }

    public function destroy($id)
    {
        dd($id);
    }
}
