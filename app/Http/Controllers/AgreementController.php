<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use Illuminate\Http\Request;

use function Ramsey\Uuid\v8;

class AgreementController extends Controller
{
 public function ShowAgreement()
 {
    return view('agreements.CreateAgreement');
 }
}
