<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alternatif;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AlternatifImport;

class AlternatifController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['alternatif'] = Alternatif::get();
        return view('admin.alternatif.index', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_alternatif' => 'required|string',
        ]);

        try {
            $alternatif = new Alternatif();
            $alternatif->nama_alternatif = $request->nama_alternatif;
            $alternatif->save();
            return redirect()->route('alternatif.index')->with('success_manual', 'Data alternatif berhasil disimpan!');

        } catch (Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            die("Gagal");
        }
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new AlternatifImport, $request->file('file'));
            return redirect()->route('alternatif.index')->with('success_import', 'Data alternatif berhasil diimpor dari file!');
        } catch (\Exception $e) {
            return back()->with('msg', 'Terjadi kesalahan saat impor: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data['alternatif'] = Alternatif::findOrFail($id);
        return view('admin.alternatif.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_alternatif' => 'required|string',
        ]);

        try {
            $alternatif = Alternatif::findOrFail($id);
            $alternatif->update([
                'nama_alternatif' => $request->nama_alternatif,
            ]);
            return back()->with('msg','Data berhasil diubah');

        } catch (Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            die("Gagal");
        }
    }

    public function destroy($id)
    {
        try {
            $alternatif = Alternatif::findOrFail($id);
            $alternatif->delete();

        } catch (Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            die("Gagal");
        }
    }

    public function deleteAll()
    {
        try {
            Alternatif::query()->delete();

            return redirect()
                ->route('alternatif.index')
                ->with('success', 'Semua data alternatif berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('alternatif.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

}
