<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class UserImportController extends Controller
{
    /**
     * Show the import form.
     *
     * @return \Illuminate\View\View
     */
    public function showImportForm()
    {
        return view('import');
    }

    /**
     * Handle the import request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return response()->json([
                'message' => 'Users imported successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to import users.',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }
}
